<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Mail\ReservationConfirmation;
use App\Models\AvisClient;
use App\Models\Chambre;
use App\Models\ClientHotel;
use App\Models\PageSite;
use App\Models\Reservation;
use App\Models\SliderHero;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReservationPubliqueController extends Controller
{
    public function index()
    {
        $sliders = SliderHero::actifs();
        $chambresDisponibles = Chambre::where('statut', 'disponible')->take(6)->get();
        $avis = AvisClient::actifs()->take(6);
        $pageAbout = PageSite::bySlug('about');
        $pageContact = PageSite::bySlug('contact');
        $stats = [
            'chambres' => Chambre::count(),
            'types' => Chambre::distinct('type')->count('type'),
            'clients' => ClientHotel::count(),
        ];

        return view('public.home', compact('sliders', 'chambresDisponibles', 'stats', 'avis', 'pageAbout', 'pageContact'));
    }

    public function aPropos()
    {
        $page = PageSite::bySlug('about');
        return view('public.a-propos', compact('page'));
    }

    public function contact()
    {
        $page = PageSite::bySlug('contact');
        return view('public.contact', compact('page'));
    }

    public function chambres(Request $request)
    {
        $query = Chambre::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('capacite')) {
            $query->where('capacite', '>=', $request->capacite);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix_nuit', '<=', $request->prix_max);
        }

        if ($request->filled('date_arrivee') && $request->filled('date_depart')) {
            $query->where('statut', 'disponible')
                ->whereDoesntHave('reservations', function ($q) use ($request) {
                    $q->whereNotIn('statut', ['annulee', 'checkout'])
                      ->where(function ($q2) use ($request) {
                          $q2->whereBetween('date_arrivee', [$request->date_arrivee, $request->date_depart])
                             ->orWhereBetween('date_depart', [$request->date_arrivee, $request->date_depart])
                             ->orWhere(function ($q3) use ($request) {
                                 $q3->where('date_arrivee', '<=', $request->date_arrivee)
                                    ->where('date_depart', '>=', $request->date_depart);
                             });
                      });
                });
        } else {
            $query->where('statut', 'disponible');
        }

        $chambres = $query->orderBy('prix_nuit')->paginate(9)->withQueryString();

        return view('public.chambres', compact('chambres'));
    }

    public function chambreDetail(Chambre $chambre)
    {
        return view('public.chambre-detail', compact('chambre'));
    }

    public function reservationForm(Chambre $chambre, Request $request)
    {
        abort_if($chambre->statut !== 'disponible', 404, 'Cette chambre n\'est plus disponible.');

        return view('public.reservation-form', [
            'chambre'      => $chambre,
            'date_arrivee' => $request->date_arrivee ?? now()->addDay()->format('Y-m-d'),
            'date_depart'  => $request->date_depart ?? now()->addDays(2)->format('Y-m-d'),
        ]);
    }

    public function reservationStore(Request $request)
    {
        $validated = $request->validate([
            'chambre_id'   => 'required|exists:chambres,id',
            'nom'          => 'required|string|max:100',
            'prenom'       => 'required|string|max:100',
            'email'        => 'required|email|max:200',
            'telephone'    => 'nullable|string|max:30',
            'nationalite'  => 'nullable|string|max:100',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart'  => 'required|date|after:date_arrivee',
            'adultes'      => 'required|integer|min:1|max:10',
            'enfants'      => 'required|integer|min:0|max:10',
            'notes'        => 'nullable|string|max:500',
        ], [
            'chambre_id.required'   => 'La chambre est obligatoire.',
            'nom.required'          => 'Le nom est obligatoire.',
            'prenom.required'       => 'Le prénom est obligatoire.',
            'email.required'        => 'L\'email est obligatoire.',
            'email.email'           => 'L\'email n\'est pas valide.',
            'date_arrivee.required' => 'La date d\'arrivée est obligatoire.',
            'date_arrivee.after_or_equal' => 'La date d\'arrivée ne peut pas être dans le passé.',
            'date_depart.required'  => 'La date de départ est obligatoire.',
            'date_depart.after'     => 'La date de départ doit être après la date d\'arrivée.',
            'adultes.min'           => 'Il faut au moins 1 adulte.',
        ]);

        $chambre = Chambre::findOrFail($validated['chambre_id']);
        $nuitees = Carbon::parse($validated['date_arrivee'])->diffInDays($validated['date_depart']);
        $montant = $nuitees * $chambre->prix_nuit;

        $client = ClientHotel::firstOrCreate(
            ['email' => $validated['email']],
            [
                'nom'         => $validated['nom'],
                'prenom'      => $validated['prenom'],
                'telephone'   => $validated['telephone'],
                'nationalite' => $validated['nationalite'],
            ]
        );

        // Créer un compte espace-client si c'est un nouveau client
        $motDePasse = null;
        $userExistait = User::where('email', $validated['email'])->exists();

        if (! $userExistait) {
            $motDePasse = Str::password(10, symbols: false);
            User::create([
                'name'     => $validated['prenom'] . ' ' . $validated['nom'],
                'email'    => $validated['email'],
                'password' => Hash::make($motDePasse),
                'role'     => Role::Client,
            ]);
        }

        $reservation = Reservation::create([
            'client_id'     => $client->id,
            'chambre_id'    => $validated['chambre_id'],
            'date_arrivee'  => $validated['date_arrivee'],
            'date_depart'   => $validated['date_depart'],
            'adultes'       => $validated['adultes'],
            'enfants'       => $validated['enfants'],
            'statut'        => 'en_attente',
            'source'        => 'direct',
            'notes'         => $validated['notes'],
            'montant_total' => $montant,
        ]);

        try {
            Mail::to($client->email)->send(new ReservationConfirmation($reservation, $motDePasse));
        } catch (\Exception $e) {
            // Email non bloquant
        }

        return redirect()->route('reservation.confirmation', $reservation)
            ->with('success', 'Votre réservation a bien été enregistrée !');
    }

    public function confirmation(Reservation $reservation)
    {
        return view('public.confirmation', compact('reservation'));
    }
}
