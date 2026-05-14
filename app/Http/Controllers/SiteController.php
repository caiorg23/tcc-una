<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiteController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha incorretos.',
        ])->onlyInput('email');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('register');
    }

    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function home()
    {
        $nextAppointment = Auth::user()
            ->appointments()
            ->with('service')
            ->where('status', '!=', 'cancelado')
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->first();

        return view('home', compact('nextAppointment'));
    }

    public function orders()
    {
        $appointments = Auth::user()
            ->appointments()
            ->with('service')
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('orders', compact('appointments'));
    }

    public function services()
    {
        $services = Service::orderBy('name')->get();
        return view('services', compact('services'));
    }

    public function support()
    {
        $faqs = [
            [
                'question' => 'Como faço para agendar um serviço?',
                'answer' => 'Vá até a página de serviços, escolha o procedimento desejado, selecione data e horário e confirme o agendamento.',
            ],
            [
                'question' => 'Posso cancelar meu agendamento?',
                'answer' => 'Sim. Em Meus Pedidos você pode cancelar um agendamento futuro clicando em Cancelar.',
            ],
            [
                'question' => 'Vocês fazem atendimento por WhatsApp?',
                'answer' => 'Sim, informamos o contato após a confirmação do agendamento e também pelo chat do site.',
            ],
            [
                'question' => 'Como saber se meu agendamento foi confirmado?',
                'answer' => 'O agendamento confirmado aparece na página inicial como seu próximo compromisso e em Meus Pedidos.',
            ],
            [
                'question' => 'Quanto tempo leva o serviço de lavagem completa?',
                'answer' => 'A lavagem completa normalmente leva entre 1h30 e 2h, dependendo do estado do veículo.',
            ],
        ];

        return view('support', compact('faqs'));
    }

    public function schedule()
    {
        $services = Service::orderBy('name')->get();
        return view('schedule', compact('services'));
    }

    public function editSchedule(Appointment $appointment)
    {
        abort_if($appointment->user_id !== Auth::id(), 403);
        $services = Service::orderBy('name')->get();
        return view('schedule', compact('services', 'appointment'));
    }

    public function scheduleConfirm(Request $request)
    {
        $cleanServiceIds = array_values(array_filter((array) $request->input('service_ids', []), fn ($id) => $id !== null && $id !== ''));
    $request->merge(['service_ids' => $cleanServiceIds]);

    $data = $request->validate([
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'date' => 'required|date|after:today|before_or_equal:' . now()->addDays(14)->toDateString(),
            'time' => 'required|string',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $serviceIds = array_values($data['service_ids']);
        $service = Service::findOrFail($serviceIds[0]);
        $selectedServices = Service::whereIn('id', $serviceIds)->get();
        $user = Auth::user();

        if (! empty($data['appointment_id'])) {
            $appointment = Appointment::where('id', $data['appointment_id'])->where('user_id', $user->id)->firstOrFail();
        } else {
            $appointment = null;
        }

        $schedule = [
            'appointment_id' => $appointment?->id,
            'service_id' => $service->id,
            'service_ids' => $serviceIds,
            'services' => $selectedServices->map(function ($service) {
                return [
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                ];
            })->toArray(),
            'service_name' => $service->name,
            'service_desc' => $service->description,
            'service_price' => $service->price,
            'date' => $data['date'],
            'time' => $data['time'],
            'location' => 'Av. Cristiano Machado, 1395 — Silveira, BH',
            'reminder' => 'WhatsApp 1 dia antes',
            'client_name' => $user->name,
            'client_email' => $user->email,
        ];

        session(['schedule' => $schedule]);

        return view('schedule-confirm', compact('schedule'));
    }

    public function scheduleDone(Request $request)
    {
        $schedule = session('schedule');
        if (! $schedule) {
            return redirect()->route('schedule');
        }

        if (! empty($schedule['appointment_id'])) {
            $appointment = Appointment::where('id', $schedule['appointment_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $appointment->update([
                'service_id' => $schedule['service_id'],
                'service_ids' => $schedule['service_ids'],
                'date' => $schedule['date'],
                'time' => $schedule['time'],
                'location' => $schedule['location'],
                'reminder' => $schedule['reminder'],
                'status' => 'confirmado',
            ]);
        } else {
            $appointment = Appointment::create([
                'user_id' => Auth::id(),
                'client_name' => $schedule['client_name'],
                'client_email' => $schedule['client_email'],
                'service_id' => $schedule['service_id'],
                'service_ids' => $schedule['service_ids'],
                'date' => $schedule['date'],
                'time' => $schedule['time'],
                'location' => $schedule['location'],
                'reminder' => $schedule['reminder'],
                'status' => 'confirmado',
            ]);
        }

        session()->forget('schedule');

        return view('schedule-done', compact('appointment'));
    }

    public function cancelAppointment(Appointment $appointment)
    {
        abort_if($appointment->user_id !== Auth::id(), 403);
        $appointment->update(['status' => 'cancelado']);
        return redirect()->route('home')->with('status', 'Agendamento cancelado com sucesso.');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
