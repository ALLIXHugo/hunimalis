<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Hunimalis Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .bg-sidebar { background-color: #2e2e48; }
        .nav-item { transition: all 0.2s; border-left: 4px solid transparent; }
        .nav-item:hover { background-color: #3b3b58; color: white; }
        .nav-item.active { background-color: #3b3b58; border-left-color: #818cf8; color: white; }
        
        .form-label { font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; display: block; }
        .form-input { 
            width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 0.875rem; 
            font-size: 0.875rem; transition: border-color 0.15s ease-in-out; background-color: white; 
        }
        .form-input:focus { outline: none; border-color: #6366f1; ring: 3px solid #e0e7ff; }
        
        .chart-container { display: flex; align-items: flex-end; gap: 4px; height: 100%; padding-bottom: 24px; padding-left: 10px; padding-right: 10px; }
        .bar-group { flex: 1; display: flex; gap: 2px; align-items: flex-end; height: 100%; position: relative; cursor: pointer; border-radius: 4px; transition: background-color 0.2s; }
        .bar-group:hover { background-color: #f8fafc; }
        .bar { width: 100%; transition: height 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); border-radius: 3px 3px 0 0; }
        .bar.recette { background-color: #818cf8; } 
        .bar.depense { background-color: #f87171; }
        
        .bar-group:hover .tooltip { display: block; animation: fadeIn 0.2s; }
        .tooltip { 
            display: none; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(-10px); 
            background: #1e293b; color: white; padding: 8px 12px; font-size: 11px; font-weight: 500; 
            border-radius: 8px; white-space: nowrap; z-index: 20; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); 
        }
        .tooltip::after {
            content: ''; position: absolute; top: 100%; left: 50%; margin-left: -6px;
            border-width: 6px; border-style: solid; border-color: #1e293b transparent transparent transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @keyframes fadeIn { from { opacity: 0; transform: translateX(-50%) translateY(0); } to { opacity: 1; transform: translateX(-50%) translateY(-10px); } }

        .toggle-checkbox:checked { right: 0; border-color: #6366f1; }
        .toggle-checkbox:checked + .toggle-label { background-color: #6366f1; }
        
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; margin: 0; 
        }
    </style>
</head>
<body class="h-full text-slate-600 antialiased" x-data="{ currentTab: '{{ session('tab') === 'marketing' || $errors->has('code') || $errors->has('type') || $errors->has('valeur') ? 'marketing' : ($errors->has('current_password') || $errors->has('password') ? 'infos' : 'dashboard') }}', sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden bg-[#f3f4f6]">

        <aside class="bg-sidebar w-64 flex-shrink-0 hidden md:flex flex-col text-slate-300 transition-all duration-300 z-20 shadow-xl">
            <div class="h-16 flex items-center px-6 bg-[#25253a] shadow-sm border-b border-slate-700/30">
                <div class="flex items-center gap-3 font-bold text-white text-lg tracking-wide">
                    <span class="text-indigo-400 text-xl"><i class="fa-solid fa-shapes"></i></span> HUNIMALIS
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 space-y-1 text-sm font-medium">
                <div class="px-6 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Principal</div>
                <a @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-gauge w-5 text-center mr-3 opacity-70"></i> Tableau de bord
                </a>
                <a @click="currentTab = 'calendar'" :class="currentTab === 'calendar' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-regular fa-calendar w-5 text-center mr-3 opacity-70"></i> Calendrier
                </a>
                
                <div class="px-6 mt-6 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Gestion</div>
                <a @click="currentTab = 'animals'" :class="currentTab === 'animals' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-paw w-5 text-center mr-3 opacity-70"></i> Animaux
                </a>
                <a @click="currentTab = 'directory'" :class="currentTab === 'directory' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-address-book w-5 text-center mr-3 opacity-70"></i> Répertoire
                </a>
                <a @click="currentTab = 'services'" :class="currentTab === 'services' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-scissors w-5 text-center mr-3 opacity-70"></i> Prestations
                </a>
                <a @click="currentTab = 'stock'" :class="currentTab === 'stock' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center mr-3 opacity-70"></i> Stock
                </a>
                <a @click="currentTab = 'factures'" :class="currentTab === 'factures' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-file w-5 text-center mr-3 opacity-70"></i> Factures
                </a>

                <div class="px-6 mt-6 mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Business</div>
                <a @click="currentTab = 'abo'; setTimeout(() => updateTotal(), 100)" :class="currentTab === 'abo' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-building-columns w-5 text-center mr-3 opacity-70"></i> Comptabilité
                </a>
                <a @click="currentTab = 'marketing'" :class="currentTab === 'marketing' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer group">
                    <i class="fa-solid fa-bullhorn w-5 text-center mr-3 opacity-70"></i> Marketing 
                    <span class="ml-auto bg-indigo-500/20 text-indigo-300 text-[9px] font-bold px-1.5 py-0.5 rounded border border-indigo-500/30">BETA</span>
                </a>
                <a @click="currentTab = 'infos'" :class="currentTab === 'infos' ? 'active' : ''" class="nav-item flex items-center px-6 py-3 cursor-pointer">
                    <i class="fa-solid fa-shop w-5 text-center mr-3 opacity-70"></i> Établissement
                </a>
            </nav>

            <div class="p-4 bg-[#25253a] border-t border-slate-700/50">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ $pro->prenompro }}+{{ $pro->nompro }}&background=6366f1&color=fff&size=64" class="h-9 w-9 rounded-full border border-slate-500/50">
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ $pro->prenompro }} {{ $pro->nompro }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ $data['type_abo'] }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden relative">
            
            <header class="bg-[#2e2e48] h-16 flex items-center justify-between px-6 shadow-md z-10 text-white flex-shrink-0">
                <div class="flex items-center gap-4 flex-1">
                    <button class="md:hidden text-white hover:text-indigo-400 transition" @click="sidebarOpen = !sidebarOpen">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    
                    <div class="relative w-full max-w-md hidden md:block group">
                        <input type="text" placeholder="Recherche rapide (Ctrl+K)..." 
                               class="w-full h-9 pl-10 pr-4 rounded-lg bg-slate-700/50 border border-slate-600 text-slate-200 text-sm focus:outline-none focus:bg-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-400">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 group-focus-within:text-indigo-400 transition-colors text-xs"></i>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-4 text-slate-300">
                        <button class="hover:text-white transition relative"><i class="fa-regular fa-bell"></i></button>
                        <button class="hover:text-white transition"><i class="fa-regular fa-circle-question"></i></button>
                    </div>
                    <div class="h-6 w-px bg-slate-600"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-red-400 transition flex items-center gap-2 font-medium text-xs uppercase tracking-wide">
                            <i class="fa-solid fa-power-off"></i> <span class="hidden sm:inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f3f4f6] p-6 scroll-smooth">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r shadow-sm flex items-center justify-between" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-600"></i> <span class="font-medium">{{ session('success') }}</span></div>
                        <button @click="show = false" class="text-green-600 hover:text-green-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r shadow-sm flex items-center justify-between" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-circle-exclamation text-red-600"></i> <span class="font-medium">{{ session('error') }}</span></div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                <div x-show="currentTab === 'dashboard'" x-cloak class="space-y-6" x-transition.opacity>                    
                    <div class="grid grid-cols-12 gap-6">
                        
                        <div class="col-span-12 lg:col-span-8 flex flex-col gap-6">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center transition hover:shadow-md cursor-pointer" @click="currentTab = 'directory'">
                                    <div class="bg-indigo-50 rounded-xl h-14 w-14 flex items-center justify-center text-indigo-600 text-2xl mr-5">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Base Clients</p>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-3xl font-bold text-slate-800">{{ count($clients) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center transition hover:shadow-md cursor-pointer" @click="currentTab = 'animals'">
                                    <div class="bg-pink-50 rounded-xl h-14 w-14 flex items-center justify-center text-pink-500 text-2xl mr-5">
                                        <i class="fa-solid fa-paw"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Animaux suivis</p>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-3xl font-bold text-slate-800">{{ count($animaux) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 sm:gap-6">
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 text-center hover:border-indigo-200 transition group">
                                    <div class="flex items-center justify-center gap-2 mb-3">
                                        <div class="bg-blue-100 text-blue-600 rounded-lg p-1.5 group-hover:bg-blue-600 group-hover:text-white transition"><i class="fa-solid fa-calendar-check text-xs"></i></div>
                                        <span class="text-xs font-bold text-slate-500 uppercase">RDV Aujourd'hui</span>
                                    </div>
                                    <p class="text-3xl font-light text-slate-800">{{ $stats['prestations_jour'] }}</p>
                                </div>

                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 text-center hover:border-green-200 transition group">
                                    <div class="flex items-center justify-center gap-2 mb-3">
                                        <div class="bg-green-100 text-green-600 rounded-lg p-1.5 group-hover:bg-green-600 group-hover:text-white transition"><i class="fa-solid fa-euro-sign text-xs"></i></div>
                                        <span class="text-xs font-bold text-slate-500 uppercase">Recettes</span>
                                    </div>
                                    <p class="text-3xl font-light text-slate-800">{{ number_format($stats['recettes_jour'], 0, ',', ' ') }} <span class="text-sm font-normal text-slate-400">€</span></p>
                                </div>

                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 text-center hover:border-red-200 transition group">
                                    <div class="flex items-center justify-center gap-2 mb-3">
                                        <div class="bg-red-100 text-red-600 rounded-lg p-1.5 group-hover:bg-red-600 group-hover:text-white transition"><i class="fa-solid fa-arrow-trend-down text-xs"></i></div>
                                        <span class="text-xs font-bold text-slate-500 uppercase">Dépenses</span>
                                    </div>
                                    <p class="text-3xl font-light text-slate-800">{{ number_format($stats['depenses_jour'], 0, ',', ' ') }} <span class="text-sm font-normal text-slate-400">€</span></p>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex-1 flex flex-col overflow-hidden">
                                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600 text-xs"><i class="fa-solid fa-history"></i></div>
                                        <h3 class="font-bold text-slate-700 text-sm">Activité récente</h3>
                                    </div>
                                    <button @click="currentTab = 'directory'" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">Voir tout l'historique</button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm text-slate-600">
                                        <thead class="bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-wider">
                                            <tr>
                                                <th class="px-6 py-3">Client</th>
                                                <th class="px-6 py-3">Ville</th>
                                                <th class="px-6 py-3">Contact</th>
                                                <th class="px-6 py-3 text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @forelse($clients->take(5) as $client)
                                            <tr class="hover:bg-slate-50 transition group cursor-pointer">
                                                <td class="px-6 py-3.5 font-medium text-slate-700">
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold">
                                                            {{ substr($client->prenom, 0, 1) }}{{ substr($client->nom, 0, 1) }}
                                                        </div>
                                                        {{ $client->prenom }} {{ $client->nom }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3.5 text-slate-500">{{ $client->ville ?? '-' }}</td>
                                                <td class="px-6 py-3.5 text-slate-500 font-mono text-xs">{{ $client->tel }}</td>
                                                <td class="px-6 py-3.5 text-right">
                                                    <button class="text-slate-300 hover:text-indigo-600 p-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm italic">Aucun client trouvé.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
                            
                            <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col border border-slate-100 min-h-[320px]">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h3 class="text-slate-800 font-bold text-sm">Trésorerie</h3>
                                        <p class="text-xs text-slate-400">Évolution sur le mois en cours</p>
                                    </div>
                                    <div class="flex gap-3 text-[9px] font-bold uppercase tracking-wide">
                                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-400"></span> Entrées</div>
                                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-400"></span> Sorties</div>
                                    </div>
                                </div>
                                
                                <div class="flex-1 relative">
                                    <div class="absolute inset-0 flex flex-col justify-between text-[9px] text-slate-300 pointer-events-none">
                                        <div class="border-b border-slate-50 w-full h-0"></div>
                                        <div class="border-b border-slate-50 w-full h-0"></div>
                                        <div class="border-b border-slate-50 w-full h-0"></div>
                                        <div class="border-b border-slate-50 w-full h-0"></div>
                                        <div class="border-b border-slate-200 w-full h-0"></div>
                                    </div>
                                    
                                    <div class="chart-container relative z-10 px-2">
                                        @foreach($financialData['labels'] as $index => $day)
                                            @php 
                                                $max = $financialData['max'] > 0 ? $financialData['max'] : 1;
                                                $hRecette = ($financialData['recettes'][$index] / $max) * 100;
                                                $hDepense = ($financialData['depenses'][$index] / $max) * 100;
                                            @endphp
                                            <div class="bar-group group" title="Jour {{ $day }}">
                                                <div class="bar recette opacity-90 group-hover:opacity-100 group-hover:bg-indigo-500" style="height: {{ $hRecette }}%"></div>
                                                <div class="bar depense opacity-90 group-hover:opacity-100 group-hover:bg-red-500" style="height: {{ $hDepense }}%"></div>
                                                
                                                <div class="tooltip">
                                                    <div class="text-slate-400 text-[9px] uppercase mb-1 border-b border-slate-700 pb-1">Le {{ $day }}</div>
                                                    <div class="flex justify-between gap-3 text-indigo-400"><span>+</span> <span>{{ $financialData['recettes'][$index] }}€</span></div>
                                                    <div class="flex justify-between gap-3 text-red-400"><span>-</span> <span>{{ $financialData['depenses'][$index] }}€</span></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-sm flex flex-col flex-1 border border-slate-100 overflow-hidden min-h-[400px]">
                                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                                    <h3 class="text-slate-800 font-bold text-base capitalize">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('MMMM YYYY') }}</h3>
                                    <button class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded" @click="currentTab = 'calendar'">Ouvrir</button>
                                </div>
                                <div class="flex-1 overflow-y-auto relative bg-white">
                                    <div class="flex h-full min-h-[500px]">
                                        <div class="w-12 border-r border-slate-50 text-[10px] text-slate-400 font-medium text-right pr-2 py-4 space-y-12 bg-slate-50/30">
                                            <div>08:00</div><div>09:00</div><div>10:00</div><div>11:00</div><div>12:00</div>
                                            <div>13:00</div><div>14:00</div><div>15:00</div><div>16:00</div><div>17:00</div>
                                        </div>
                                        <div class="flex-1 relative">
                                            @for($i=0; $i<10; $i++)
                                                <div class="absolute w-full border-b border-slate-50 h-14" style="top: {{ $i * 56 }}px"></div>
                                            @endfor
                                            
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $currentHour = $now->hour;
                                                $currentMin = $now->minute;
                                                $topCurrent = (($currentHour - 8) * 56) + (($currentMin / 60) * 56);
                                            @endphp
                                            @if($currentHour >= 8 && $currentHour <= 18)
                                                <div class="absolute w-full border-b border-red-400 z-20 flex items-center" style="top: {{ $topCurrent }}px">
                                                    <div class="h-1.5 w-1.5 bg-red-400 rounded-full -ml-[3px]"></div>
                                                </div>
                                            @endif

                                            @php
                                                $rdvsToday = collect($calendarData['planning'])->flatten(1)->filter(function($rdv) {
                                                    return true; 
                                                });
                                                $dayToday = \Carbon\Carbon::now()->day;
                                                $todayPlanning = $calendarData['planning'][$dayToday] ?? [];
                                            @endphp

                                            @if(count($todayPlanning) > 0)
                                                @foreach($todayPlanning as $rdv)
                                                    @php
                                                        $heureParts = explode(':', $rdv['heure']);
                                                        $h = intval($heureParts[0]);
                                                        $m = intval($heureParts[1]);
                                                        $topPos = (($h - 8) * 56) + (($m / 60) * 56);
                                                        
                                                        $height = ($rdv['duree'] / 60) * 56;
                                                        if($height < 25) $height = 25;
                                                    @endphp
                                                    <div class="absolute left-2 right-2 border-l-4 border-indigo-500 p-2 rounded-r shadow-sm cursor-pointer hover:bg-indigo-100 transition group bg-indigo-50/90 z-10 overflow-hidden"
                                                         style="top: {{ $topPos }}px; height: {{ $height }}px;">
                                                        <p class="text-xs font-bold text-indigo-700 flex justify-between">
                                                            <span>{{ $rdv['heure'] }} - {{ $rdv['prestation'] }}</span>
                                                        </p>
                                                        <p class="text-[10px] text-indigo-500 truncate">{{ $rdv['client'] }} - {{ $rdv['animal'] }}</p>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="flex flex-col items-center justify-center h-full text-slate-300 pb-10">
                                                    <i class="fa-regular fa-calendar-check text-3xl mb-2 opacity-50"></i>
                                                    <p class="text-xs">Aucun rendez-vous aujourd'hui</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'calendar'" class="h-full flex flex-col" x-cloak>
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 capitalize">{{ $calendarData['monthName'] }} {{ $calendarData['year'] }}</h2>
                            <p class="text-sm text-slate-500">Planning mensuel des rendez-vous</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="bg-white border border-slate-200 text-slate-600 px-3 py-2 rounded-lg hover:bg-slate-50 transition"><i class="fa-solid fa-chevron-left"></i></button>
                            <button class="bg-white border border-slate-200 text-slate-600 px-3 py-2 rounded-lg hover:bg-slate-50 transition"><i class="fa-solid fa-chevron-right"></i></button>
                            <a href="{{ route('rdv.employe.planning') }}" class="ml-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition flex items-center text-sm">
    <i class="fa-solid fa-plus mr-2"></i> Nouveau RDV
</a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex-1 flex flex-col overflow-hidden">
                        
                        <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50">
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase">Lun</div>
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase">Mar</div>
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase">Mer</div>
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase">Jeu</div>
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase">Ven</div>
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase">Sam</div>
                            <div class="py-3 text-center text-xs font-bold text-slate-500 uppercase text-red-400">Dim</div>
                        </div>

                        <div class="grid grid-cols-7 flex-1 auto-rows-fr bg-slate-100 gap-px border-l border-slate-200">
                            
                            @for($i = 0; $i < $calendarData['emptyCellsStart']; $i++)
                                <div class="bg-slate-50/50 min-h-[100px]"></div>
                            @endfor

                            @for($day = 1; $day <= $calendarData['daysInMonth']; $day++)
                                @php 
                                    $isToday = $day == date('j') && $calendarData['monthName'] == ucfirst(\Carbon\Carbon::now()->locale('fr')->monthName);
                                    $hasRdv = isset($calendarData['planning'][$day]);
                                @endphp
                                <div class="bg-white min-h-[100px] p-2 relative hover:bg-slate-50 transition group cursor-pointer flex flex-col gap-1 overflow-hidden">
                                    <div class="flex justify-between items-start">
                                        <span class="text-sm font-medium h-7 w-7 flex items-center justify-center rounded-full {{ $isToday ? 'bg-indigo-600 text-white' : 'text-slate-700' }}">
                                            {{ $day }}
                                        </span>
                                        @if($hasRdv)
                                            <span class="text-[9px] font-bold text-slate-400">{{ count($calendarData['planning'][$day]) }} RDV</span>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex flex-col gap-1 mt-1 overflow-y-auto custom-scrollbar">
                                        @if($hasRdv)
                                        @foreach($calendarData['planning'][$day] as $rdv)
                                        @php
                                            $rdvTime = \Carbon\Carbon::createFromFormat('H:i', $rdv['heure']);
                                            $startOfDay = \Carbon\Carbon::createFromFormat('H:i', '08:00'); 
                                            
                                            $diffMinutes = $startOfDay->diffInMinutes($rdvTime, false);
                                            if($diffMinutes < 0) $diffMinutes = 0; 
                                            
                                            
                                            $pixelsPerMinute = 80 / 60; // Exemple : si une heure fait 80px de haut
                                            
                                            $topPos = ($diffMinutes / 60) * 128; // Si row-span-1 fait une certaine hauteur.
                                            
                                            // Calcul basé sur une hauteur de ligne de 80px par heure (exemple)
                                            $topPos = ($diffMinutes / 60) * 80; 
                                            $height = ($rdv['duree'] / 60) * 80;
                                        @endphp

                                        <div onclick="openRdvActionModal({{ $rdv['id'] }})" 
                                            class="absolute left-1 right-1 border-l-4 {{ isset($rdv['statut']) && $rdv['statut'] == 3 ? 'border-red-500 bg-red-50' : (isset($rdv['statut']) && $rdv['statut'] == 4 ? 'border-green-500 bg-green-50' : 'border-indigo-500 bg-indigo-50') }} px-2 py-1 rounded shadow-sm cursor-pointer hover:brightness-95 transition z-10 overflow-hidden text-xs leading-tight block"
                                            style="top: {{ $topPos }}px; height: {{ $height }}px;">
                                            
                                            <span class="font-bold">{{ $rdv['heure'] }}</span><br>
                                            <span class="truncate">{{ $rdv['client'] }}</span>
                                        </div>
                                    @endforeach
                                        @endif
                                    </div>

                                    <button onclick="openCreateRdvModal()" class="absolute bottom-2 right-2 text-indigo-400 opacity-0 group-hover:opacity-100 hover:text-indigo-600 transition bg-white rounded-full shadow-sm p-1">
                                        <i class="fa-solid fa-plus-circle text-lg"></i>
                                    </button>
                                </div>
                            @endfor
                            
                            @php 
                                $totalCells = $calendarData['emptyCellsStart'] + $calendarData['daysInMonth'];
                                $remainingCells = 35 - $totalCells; 
                                if($remainingCells < 0) $remainingCells = 42 - $totalCells;
                            @endphp
                            @for($j = 0; $j < $remainingCells; $j++)
                                <div class="bg-slate-50/50 min-h-[100px]"></div>
                            @endfor

                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'animals'" style="display: none;" x-cloak x-data="{ search: '' }">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
                            <div>
                                <h2 class="font-bold text-lg text-slate-800">Nos pensionnaires</h2>
                                <p class="text-xs text-slate-500 mt-1">Gérez les fiches médicales.</p>
                            </div>
                            <div class="flex gap-3 w-full sm:w-auto">
                                <div class="relative w-full sm:w-64">
                                    <input type="text" x-model="search" placeholder="Nom, tatouage, propriétaire..." class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                                </div>
                                <button onclick="openCreateAnimalModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex flex-shrink-0 gap-2">
                                    <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Ajouter</span>
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-wider">
                                    <tr><th class="px-6 py-4">Animal</th><th class="px-6 py-4">Identification</th><th class="px-6 py-4">Propriétaire</th><th class="px-6 py-4 text-right">Actions</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($animaux as $animal)
                                    @php $proprio = $clients->firstWhere('idclient', $animal->idclient); @endphp
                                    <tr class="hover:bg-slate-50 transition group" x-show="search === '' || $el.textContent.toLowerCase().includes(search.toLowerCase())">
                                        <td class="px-6 py-4 font-medium text-slate-700">
                                            <div class="flex items-center gap-4">
                                                <div class="bg-indigo-50 text-indigo-500 h-10 w-10 rounded-xl flex items-center justify-center shadow-sm"><i class="fa-solid fa-dog text-lg"></i></div>
                                                <div><div class="font-bold text-base">{{ $animal->nom1animal }}</div><div class="text-xs text-slate-400">{{ $animal->sexe ? 'Mâle' : 'Femelle' }}</div></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><div class="flex items-center gap-2 text-xs font-mono bg-slate-100 w-fit px-2 py-1 rounded text-slate-500"><i class="fa-solid fa-fingerprint"></i> {{ $animal->numtatouage ?? 'Non identifié' }}</div></td>
                                        <td class="px-6 py-4">
                                            @if($proprio) <div class="flex items-center gap-2"><div class="h-6 w-6 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] font-bold">{{ substr($proprio->prenom, 0, 1) }}</div><span class="text-sm text-slate-600 font-medium">{{ $proprio->prenom }} {{ $proprio->nom }}</span></div> 
                                            @else <span class="text-xs text-slate-400 italic">Propriétaire inconnu</span> @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition">
                                                <a href="{{ route('animal.show', $animal->numtatouage) }}" class="h-8 w-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 transition flex items-center justify-center">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'directory'" style="display: none;" x-cloak x-data="{ search: '' }">
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100" x-data="{ activeTab: 'clients' }">
                
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <h2 class="text-xl font-bold text-gray-800">Répertoires</h2>
                        
                        <div class="flex bg-gray-100 p-1 rounded-lg">
                            <button @click="activeTab = 'clients'" 
                                    :class="activeTab === 'clients' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-4 py-1.5 rounded-md text-sm font-medium transition-all">
                                Clients
                            </button>
                            <button @click="activeTab = 'prestataires'" 
                                    :class="activeTab === 'prestataires' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-4 py-1.5 rounded-md text-sm font-medium transition-all">
                                Prestataires
                            </button>
                        </div>
                    </div>

                    <div>
                        <div x-show="activeTab === 'clients'">
                            <button @click="openModal('addClientModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                                <i class="fa-solid fa-plus"></i> Nouveau Client
                            </button>
                        </div>
                        <div x-show="activeTab === 'prestataires'" x-cloak>
                            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
                                <i class="fa-solid fa-user-plus"></i> Nouveau Prestataire
                            </a>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'clients'" class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4 font-semibold">Client</th>
                                <th class="px-6 py-4 font-semibold">Contact</th>
                                <th class="px-6 py-4 font-semibold">Ville</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($clients as $client)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                            {{ substr($client->nom ?? 'C', 0, 1) }}{{ substr($client->prenom ?? '', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $client->nom }} {{ $client->prenom }}</p>
                                            <p class="text-xs text-gray-400">#{{ $client->idclient }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col gap-1">
                                        <span class="flex items-center gap-2"><i class="fa-solid fa-phone text-gray-300 text-xs"></i> {{ $client->tel ?? 'N/C' }}</span>
                                        <span class="flex items-center gap-2"><i class="fa-solid fa-envelope text-gray-300 text-xs"></i> {{ $client->mail ?? 'N/C' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $client->ville ?? 'Non renseigné' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-gray-400 hover:text-blue-600 transition mx-1"><i class="fa-solid fa-eye"></i></button>
                                    <button class="text-gray-400 hover:text-amber-500 transition mx-1"><i class="fa-solid fa-pen"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucun client trouvé.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if(method_exists($clients, 'links'))
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $clients->links() }}
                        </div>
                    @endif
                </div>

                <div x-show="activeTab === 'prestataires'" x-cloak class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4 font-semibold">Collaborateur</th>
                                <th class="px-6 py-4 font-semibold">Fonction</th>
                                <th class="px-6 py-4 font-semibold">Coordonnées</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @if(isset($employes) && count($employes) > 0)
                                @foreach($employes as $employe)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                {{ substr($employe->personne->nom ?? 'E', 0, 1) }}{{ substr($employe->personne->prenom ?? '', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $employe->personne->nom }} {{ $employe->personne->prenom }}</p>
                                                <p class="text-xs text-gray-400">ID: Emp-{{ $employe->idemploye }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $employe->fonction ?? 'Collaborateur' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-2"><i class="fa-solid fa-phone text-gray-300 text-xs"></i> {{ $employe->personne->tel1 ?? '-' }}</span>
                                            <span class="flex items-center gap-2"><i class="fa-solid fa-envelope text-gray-300 text-xs"></i> {{ $employe->personne->mail ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#" class="text-gray-400 hover:text-indigo-600 transition mx-1" title="Voir planning">
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </a>
                                        <a href="#" class="text-gray-400 hover:text-amber-500 transition mx-1" title="Modifier">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                        <i class="fa-solid fa-users-slash text-2xl mb-2 text-gray-300"></i><br>
                                        Aucun prestataire enregistré.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
                </div>

                <div x-show="currentTab === 'services'" style="display: none;" x-cloak x-data="{ search: '' }">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
                            <div>
                                <h2 class="font-bold text-lg text-slate-800">Catalogue des Services</h2>
                                <p class="text-xs text-slate-500 mt-1">Gérez les prestations proposées à vos clients.</p>
                            </div>
                            <div class="flex gap-3 w-full sm:w-auto">
                                <div class="relative w-full sm:w-64">
                                    <input type="text" x-model="search" placeholder="Nom, catégorie..." class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                                </div>
                                <button onclick="openCreatePrestationModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex items-center gap-2 flex-shrink-0">
                                    <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Nouveau Service</span>
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-wider">
                                    <tr><th class="px-6 py-4">Nom du service</th><th class="px-6 py-4">Catégorie</th><th class="px-6 py-4">Espèce</th><th class="px-6 py-4">Durée</th><th class="px-6 py-4 text-right">Tarif HT</th><th class="px-6 py-4 text-right">Action</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($prestations as $presta)
                                    <tr class="hover:bg-slate-50 transition group" x-show="search === '' || $el.textContent.toLowerCase().includes(search.toLowerCase())">
                                        <td class="px-6 py-4 font-bold text-slate-700">{{ $presta->nomprestation }}</td>
                                        <td class="px-6 py-4"><span class="bg-slate-100 px-2 py-1 rounded text-xs font-medium text-slate-500 uppercase">{{ $presta->libellecategorie }}</span></td>
                                        <td class="px-6 py-4 text-slate-500">{{ $presta->libelleespece }}</td>
                                        <td class="px-6 py-4 text-slate-500"><i class="fa-regular fa-clock mr-1"></i> {{ $presta->duree }} min</td>
                                        <td class="px-6 py-4 text-right font-black text-indigo-600">{{ number_format($presta->tarifht, 2) }} €</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition">
                                                <button onclick="openEditPrestationModal({{ json_encode($presta) }})" class="h-8 w-8 rounded-lg border border-slate-200 text-slate-400 hover:text-indigo-600 transition flex items-center justify-center">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'stock'" style="display: none;" x-cloak x-data="{ search: '' }">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden min-h-[600px] flex flex-col">
                        
                        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white">
                            <div>
                                <h2 class="font-bold text-lg text-slate-800">Gestion des Stocks</h2>
                                <p class="text-xs text-slate-500 mt-1">Mise à jour rapide des quantités et références.</p>
                            </div>
                            <div class="flex gap-3 w-full sm:w-auto">
                                <div class="relative w-full sm:w-80">
                                    <input type="text" x-model="search" placeholder="Rechercher un produit (Ctrl+K)..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                </div>
                                <button onclick="openCreateProductModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-sm transition flex items-center gap-2 flex-shrink-0">
                                    <i class="fa-solid fa-plus"></i> <span>Nouveau</span>
                                </button>
                                <button onclick="openVenteModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-sm transition flex items-center gap-2 flex-shrink-0 ml-2">
                                    <i class="fa-solid fa-cash-register"></i> <span>Nouvelle Vente</span>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50/50 flex-1">
                            @if(isset($produits) && count($produits) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                    @foreach($produits as $produit)
                                    <div class="bg-white border border-slate-200 rounded-xl p-4 hover:shadow-md transition group flex flex-col h-full" 
                                         x-show="search === '' || '{{ strtolower($produit->nomproduit) }}'.includes(search.toLowerCase()) || '{{ strtolower($produit->numserie ?? '') }}'.includes(search.toLowerCase())">
                                        
                                        <div class="relative w-full aspect-square bg-slate-50 rounded-lg overflow-hidden flex items-center justify-center border border-slate-100 mb-4 group-hover:border-indigo-100 transition">
                                            @if($produit->photo)
                                                <img src="{{ asset('storage/' . $produit->photo) }}" alt="{{ $produit->nomproduit }}" class="w-full h-full object-contain p-4">
                                            @else
                                                <i class="fa-solid fa-image text-4xl text-slate-200 group-hover:text-indigo-200 transition"></i>
                                            @endif
                                            
                                            @if($produit->libellecategoriepro)
                                            <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 rounded-md shadow-sm border border-slate-100">
                                                {{ $produit->libellecategoriepro }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 flex flex-col">
                                            <div class="mb-auto">
                                                <h3 class="font-bold text-slate-800 text-base leading-tight mb-1 line-clamp-2" title="{{ $produit->nomproduit }}">{{ $produit->nomproduit }}</h3>
                                                <div class="flex flex-wrap gap-2 text-[10px] text-slate-400 mt-2">
                                                    @if($produit->numserie)
                                                        <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 font-mono border border-slate-200">{{ $produit->numserie }}</span>
                                                    @endif
                                                    @if($produit->numlot)
                                                        <span class="flex items-center gap-1"><i class="fa-solid fa-box-open"></i> {{ $produit->numlot }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-4 pt-4 border-t border-slate-50">
                                                <div class="flex justify-between items-end mb-3">
                                                    <span class="text-lg font-black text-slate-700">{{ number_format($produit->prixvente, 2) }} €</span>
                                                    
                                                    @if($produit->stocks <= $produit->seuilalerte)
                                                        <span class="text-[10px] font-bold text-red-500 flex items-center gap-1 animate-pulse bg-red-50 px-2 py-1 rounded">
                                                            <i class="fa-solid fa-triangle-exclamation"></i> Critique
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] font-bold text-green-600 flex items-center gap-1 bg-green-50 px-2 py-1 rounded">
                                                            <i class="fa-solid fa-check"></i> En Stock
                                                        </span>
                                                    @endif
                                                </div>

                                                <form action="{{ route('produit.stock.update', $produit->idproduit) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                                                        <button type="button" onclick="this.nextElementSibling.stepDown()" class="w-8 h-8 rounded-md bg-white text-slate-500 hover:text-red-500 shadow-sm border border-slate-200 transition flex items-center justify-center hover:bg-red-50 hover:border-red-100">
                                                            <i class="fa-solid fa-minus text-xs"></i>
                                                        </button>
                                                        
                                                        <input type="number" name="nouveau_stock" value="{{ $produit->stocks }}" 
                                                               class="flex-1 h-8 text-center bg-transparent font-bold text-slate-700 focus:outline-none border-none p-0 text-sm appearance-none w-full" min="0">
                                                        
                                                        <button type="button" onclick="this.previousElementSibling.stepUp()" class="w-8 h-8 rounded-md bg-white text-slate-500 hover:text-green-600 shadow-sm border border-slate-200 transition flex items-center justify-center hover:bg-green-50 hover:border-green-100">
                                                            <i class="fa-solid fa-plus text-xs"></i>
                                                        </button>

                                                        <button type="submit" class="w-8 h-8 rounded-md bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 transition flex items-center justify-center ml-1" title="Enregistrer">
                                                            <i class="fa-solid fa-check text-xs"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full min-h-[400px] text-center">
                                    <div class="bg-white p-6 rounded-full shadow-sm mb-6 border border-slate-100 h-24 w-24 flex items-center justify-center">
                                        <i class="fa-solid fa-box-open text-4xl text-indigo-100"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Aucun produit en stock</h3>
                                    <p class="text-slate-400 max-w-sm mx-auto mb-8">Commencez par ajouter vos produits pour gérer votre inventaire ici.</p>
                                    
                                    <button onclick="openCreateProductModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center gap-2 transform hover:-translate-y-1">
                                        <i class="fa-solid fa-plus"></i> Ajouter un premier produit
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'factures'" x-cloak class="space-y-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">Gestion des Factures</h2>
                        
                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <div class="relative flex-1 md:w-64">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" id="searchFactureClient" onkeyup="filterFactures()" placeholder="N° Facture, N° Client, Nom..." 
                                    class="pl-10 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm p-2.5">
                            </div>
                            <div class="relative flex-1 md:w-48">
                                <input type="date" id="searchFactureDate" onchange="filterFactures()" 
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm p-2.5">
                            </div>
                            <button onclick="resetFilters()" class="p-2.5 text-gray-500 hover:text-indigo-600 bg-white rounded-lg border border-gray-300 shadow-sm">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200" id="tableFactures">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N<sup>o</sup> facture</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($factures as $f)
                                <tr class="facture-row hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 facture-num">
                                        #{{ $f->idfacture }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 facture-date" data-date="{{ \Carbon\Carbon::parse($f->date_facture)->format('Y-m-d') }}">
                                        {{ \Carbon\Carbon::parse($f->date_facture)->format('d/m/Y') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap facture-client">
                                        <div class="text-sm font-medium text-gray-900">{{ $f->client->personne->nom }} {{ $f->client->personne->prenom }}</div>
                                        <div class="text-xs text-gray-500">{{ $f->client->personne->mail }}</div>
                                        <div class="text-xs text-indigo-600 font-semibold"><span class="facture-id">{{ $f->idclient }}</span></div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ number_format($f->total, 2, ',', ' ') }} €
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $f->statut === 'payee' ? 'bg-green-100 text-green-800' : ($f->statut === 'annulee' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($f->statut ?? 'En attente') }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('facture.download', $f->idfacture) }}" title="Télécharger PDF" class="text-red-600 hover:text-red-900 text-lg">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>

                                        @if($f->statut !== 'payee' && $f->statut !== 'annulee' && $f->statut !== 'avoir')
                                            <button onclick="openEditFactureModal({{ json_encode($f) }})" title="Modifier l'en-tête" class="text-slate-500 hover:text-indigo-600 text-lg">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        @endif

                                        <a href="{{ route('facture.mail', $f->idfacture) }}" title="Envoyer par mail" class="text-indigo-600 hover:text-indigo-900 text-lg">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </a>

                                        @if($f->statut !== 'payee' && $f->statut !== 'annulee' && $f->statut !== 'avoir')

                                            <form action="{{ route('facture.valider', $f->idfacture) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="Valider le paiement" class="text-green-600 hover:text-green-900 text-lg">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('facture.annuler', $f->idfacture) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette facture sans crédit ?')">
                                                @csrf
                                                <button type="submit" title="Annuler" class="text-gray-400 hover:text-red-600 text-lg">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('facture.creerAvoir', $f->idfacture) }}" method="POST" class="inline" onsubmit="return confirm('Créer un avoir pour cette facture ? Le montant sera crédité au client.');">
                                                @csrf
                                                <button type="submit" title="Litige : Créer un Avoir" class="text-amber-500 hover:text-amber-700 text-lg ml-1">
                                                    <i class="fa-solid fa-scale-balanced"></i>
                                                </button>
                                            </form>

                                        @elseif($f->statut === 'avoir')
                                            <span class="text-xs font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded">Avoir</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="currentTab === 'marketing'" style="display: none;" x-cloak>
                    <div class="max-w-6xl mx-auto space-y-6">
                        
                        <div class="bg-indigo-600 rounded-2xl p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                            <div class="relative z-10">
                                <h2 class="text-2xl font-bold mb-2">Codes Promotionnels</h2>
                                <p class="text-indigo-100 text-sm max-w-lg">Fidélisez vos clients en créant des remises exclusives. Les codes peuvent être en pourcentage ou en montant fixe.</p>
                            </div>
                            <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/20 relative z-10">
                                <i class="fa-solid fa-tags text-4xl text-white"></i>
                            </div>
                            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500 rounded-full opacity-50 blur-3xl"></div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <div class="lg:col-span-1">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
                                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-plus-circle text-indigo-500"></i> Créer un code
                                    </h3>
                                    
                                    <form action="{{ route('codepromo.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="form-label">Code (Ex: ETE2024)</label>
                                            <input type="text" name="code" class="form-input uppercase font-bold text-slate-700" placeholder="MONCODE20" required>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="form-label">Type</label>
                                                <select name="type" class="form-input" required>
                                                    <option value="pourcentage">Remise (%)</option>
                                                    <option value="euro">Remise (€)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">Valeur</label>
                                                <input type="number" step="0.01" name="valeur" class="form-input" placeholder="10" required>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="form-label">Date limite (Optionnel)</label>
                                            <input type="date" name="date_fin" class="form-input text-sm">
                                        </div>

                                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-3 rounded-xl font-bold shadow-lg transition flex justify-center items-center gap-2">
                                            <span>Créer la promotion</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="lg:col-span-2">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <h3 class="font-bold text-slate-800">Codes Actifs</h3>
                                        <span class="text-xs font-bold bg-indigo-100 text-indigo-600 px-2 py-1 rounded-md">{{ count($codepromos) }} codes</span>
                                    </div>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm text-slate-600">
                                            <thead class="bg-slate-50 text-slate-400 font-bold text-[10px] uppercase tracking-wider">
                                                <tr>
                                                    <th class="px-6 py-4">Code</th>
                                                    <th class="px-6 py-4">Réduction</th>
                                                    <th class="px-6 py-4">Validité</th>
                                                    <th class="px-6 py-4 text-right">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @forelse($codepromos as $promo)
                                                <tr class="hover:bg-slate-50 transition group">
                                                    <td class="px-6 py-4">
                                                        <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-100 select-all">
                                                            {{ $promo->code }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 font-bold text-slate-700">
                                                        @if($promo->type == 'pourcentage')
                                                            -{{ $promo->valeur }} %
                                                        @else
                                                            -{{ number_format($promo->valeur, 2) }} €
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @if($promo->date_fin)
                                                            @if(\Carbon\Carbon::parse($promo->date_fin)->isPast())
                                                                <span class="text-red-500 font-bold text-xs"><i class="fa-solid fa-circle-exclamation"></i> Expiré le {{ \Carbon\Carbon::parse($promo->date_fin)->format('d/m/Y') }}</span>
                                                            @else
                                                                <span class="text-green-600 text-xs flex items-center gap-1"><i class="fa-regular fa-clock"></i> Jusqu'au {{ \Carbon\Carbon::parse($promo->date_fin)->format('d/m/Y') }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-slate-400 text-xs italic">Illimité</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        <form action="{{ route('codepromo.destroy', $promo->idcodepromo) }}" method="POST" onsubmit="return confirm('Supprimer ce code promo ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-slate-300 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">
                                                        <div class="mb-2"><i class="fa-solid fa-ticket text-2xl opacity-20"></i></div>
                                                        Aucun code promo actif.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="currentTab === 'abo'" style="display: none;" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-slate-100 max-w-6xl mx-auto">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 pb-6 border-b border-slate-100 gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800">Mon Abonnement</h2>
                                <p class="text-sm text-slate-500 mt-1">Gérez votre offre et les fonctionnalités activées.</p>
                            </div>
                            <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide border border-green-200 flex items-center gap-2 w-fit">
                                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> {{ $data['statut'] }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition"><i class="fa-solid fa-crown text-4xl"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Offre actuelle</p>
                                <p class="text-xl font-black text-slate-800">{{ $data['type_abo'] }}</p>
                            </div>
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition"><i class="fa-solid fa-calendar-check text-4xl"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Renouvellement</p>
                                <p class="text-xl font-black text-slate-800">{{ $data['prochaine_echeance'] }}</p>
                            </div>
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition"><i class="fa-solid fa-comment-sms text-4xl"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Solde SMS</p>
                                <p class="text-xl font-black text-indigo-600">{{ $data['credit_sms'] }}</p>
                            </div>
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition"><i class="fa-solid fa-file-invoice-dollar text-4xl"></i></div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Prochaine Facture</p>
                                <p class="text-xl font-black text-slate-800" id="headerTotal">-- €</p>
                            </div>
                        </div>

                        <form action="{{ route('professionnel.updateAbonnement') }}" method="POST" id="subscriptionForm">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <div class="lg:col-span-2 space-y-8">
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-lg mb-4 flex items-center gap-2">
                                            <div class="bg-indigo-600 h-6 w-1 rounded"></div> Modules additionnels
                                        </h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            @foreach($data['modules_disponibles'] as $module)
                                                @php 
                                                    $isInclus = $module->pivot->est_inclus;
                                                    $isChecked = $isInclus || in_array($module->idmodule, $data['subscribed_ids']);
                                                @endphp
                                                <label class="relative bg-white border {{ $isChecked ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50/10' : 'border-slate-200' }} rounded-xl p-5 cursor-pointer hover:shadow-md transition group select-none">
                                                    <div class="flex justify-between items-start mb-3">
                                                        <div class="bg-white border border-slate-100 p-2.5 rounded-lg text-slate-500 group-hover:text-indigo-600 group-hover:border-indigo-100 transition shadow-sm">
                                                            @if(str_contains(strtolower($module->nommodule), 'stock')) <i class="fa-solid fa-boxes-stacked text-lg"></i>
                                                            @elseif(str_contains(strtolower($module->nommodule), 'facturation')) <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                                                            @else <i class="fa-solid fa-puzzle-piece text-lg"></i> @endif
                                                        </div>
                                                        @if(!$isInclus)
                                                            <div class="relative inline-block w-11 align-middle select-none transition duration-200 ease-in">
                                                                <input type="checkbox" name="modules[]" value="{{ $module->idmodule }}" data-price="{{ $module->prixmodule }}" onchange="updateTotal()" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300 checked:right-0 checked:border-indigo-600 transition-all duration-300 shadow-sm" {{ $isChecked ? 'checked' : '' }}/>
                                                                <label class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
                                                            </div>
                                                        @else
                                                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wide flex items-center gap-1"><i class="fa-solid fa-check"></i> Inclus</span>
                                                        @endif
                                                    </div>
                                                    <h4 class="font-bold text-slate-800 mb-1 text-sm">{{ $module->nommodule }}</h4>
                                                    @if(!$isInclus)
                                                        <p class="text-xs text-slate-500 font-medium">+ {{ number_format($module->prixmodule, 2) }} € HT / mois</p>
                                                    @else
                                                        <p class="text-xs text-green-600 font-medium">Compris dans votre offre</p>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6 shadow-lg">
                                        <div class="relative z-10 flex items-center gap-4">
                                            <div class="bg-white/10 p-4 rounded-xl backdrop-blur-sm"><i class="fa-solid fa-comment-sms text-2xl text-indigo-400"></i></div>
                                            <div>
                                                <h4 class="font-bold text-lg">Besoin de SMS ?</h4>
                                                <p class="text-sm text-slate-400">Boostez votre marketing avec nos packs.</p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="openSmsModal()" class="relative z-10 bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-bold transition shadow-lg transform hover:-translate-y-0.5">
                                            Recharger
                                        </button>
                                        <div class="absolute top-0 right-0 -mr-10 -mt-10 h-40 w-40 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-1">
                                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xl sticky top-6">
                                        <p class="text-xs font-bold text-slate-400 uppercase mb-6 tracking-wider border-b border-slate-100 pb-2">Total estimé</p>
                                        
                                        <div class="flex items-baseline gap-2 mb-2">
                                            <span class="text-4xl font-black tracking-tight text-slate-800" id="totalPriceDisplay">-- €</span>
                                            <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2 py-1 rounded" id="periodDisplay">HT/mois</span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mb-6">TVA non incluse dans l'affichage HT</p>
                                        
                                        <div class="bg-slate-50 p-4 rounded-xl flex items-center justify-between mb-6 border border-slate-100 cursor-pointer hover:bg-slate-100 transition" onclick="document.getElementById('subscriptionToggle').click()">
                                            <span class="text-xs font-bold uppercase text-slate-600">Mensuel</span>
                                            <label class="relative inline-flex items-center cursor-pointer pointer-events-none">
                                                <input type="checkbox" name="mode_paiement_annuel" id="subscriptionToggle" class="sr-only peer" {{ $data['is_annuel'] ? 'checked' : '' }} onchange="updateTotal()">
                                                <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </label>
                                            <div class="text-right">
                                                <span class="text-xs font-bold uppercase text-slate-600">Annuel</span>
                                                <span class="block text-[9px] font-bold text-green-600 bg-green-50 px-1 rounded">-20%</span>
                                            </div>
                                        </div>
                                        
                                        <button type="button" onclick="openPaymentModal()" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-4 rounded-xl font-bold text-sm shadow-lg transition transform active:scale-[0.98] flex items-center justify-center gap-2">
                                            <span>Mettre à jour</span> <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                        <p class="text-[10px] text-center text-slate-400 mt-4 flex items-center justify-center gap-1"><i class="fa-solid fa-lock"></i> Paiement sécurisé</p>
                                    </div>
                                </div>
                            </div>

                            <div id="paymentModal" class="fixed inset-0 z-50 hidden" x-cloak>
                                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePaymentModal()"></div>
                                <div class="flex min-h-full items-center justify-center p-4">
                                    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full relative z-10">
                                        <div class="text-center mb-6">
                                            <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fa-solid fa-credit-card text-xl"></i></div>
                                            <h3 class="text-xl font-bold text-slate-800">Confirmation</h3>
                                            <p class="text-sm text-slate-500">Validez votre nouvel abonnement.</p>
                                        </div>
                                        
                                        <div class="bg-slate-50 p-4 rounded-xl mb-6 flex justify-between items-center border border-slate-100">
                                            <span class="text-sm font-bold text-slate-600">Montant total TTC</span>
                                            <span class="text-2xl font-black text-indigo-600" id="modalTotalAmount">-- €</span>
                                        </div>

                                        <div class="space-y-4 mb-8">
                                            <div>
                                                <label class="form-label text-xs uppercase">Numéro de carte</label>
                                                <div class="relative">
                                                    <input type="text" name="cb_numero" placeholder="0000 0000 0000 0000" class="form-input pl-10 font-mono">
                                                    <i class="fa-brands fa-cc-visa absolute left-3 top-3 text-slate-400 text-lg"></i>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="form-label text-xs uppercase">Expiration</label>
                                                    <input type="text" name="cb_expiration" value="{{ $pro->cb_expiration }}" placeholder="MM/AA" class="form-input text-center font-mono">
                                                </div>
                                                <div>
                                                    <label class="form-label text-xs uppercase">CVC</label>
                                                    <input type="text" name="cb_cvv" placeholder="123" class="form-input text-center font-mono">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="button" onclick="closePaymentModal()" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-50 rounded-xl transition">Annuler</button>
                                            <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">Payer maintenant</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="currentTab === 'infos'" style="display:none" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-slate-100 max-w-4xl mx-auto space-y-8">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800">Profil Établissement</h2>
                                <p class="text-sm text-slate-500">Ces informations apparaissent sur vos factures.</p>
                            </div>
                            <button form="etablissementForm" type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center gap-2">
                                <i class="fa-solid fa-save"></i> Enregistrer
                            </button>
                        </div>
                        
                        <form id="etablissementForm" action="{{ route('professionnel.updateEtablissement') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                    <h3 class="font-bold text-slate-700 mb-4 text-xs uppercase tracking-wide flex items-center gap-2"><i class="fa-solid fa-id-card"></i> Identité légale</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div><label class="form-label">Nom Commercial</label><input type="text" name="nom" value="{{ $pro->libelleetablissement }}" class="form-input"></div>
                                        <div><label class="form-label">SIRET</label><input type="text" value="{{ $pro->siret }}" class="form-input bg-slate-100 text-slate-500 cursor-not-allowed border-slate-200" readonly></div>
                                    </div>
                                </div>

                                <div><label class="form-label">Email Professionnel</label><input type="email" name="melpro" value="{{ $pro->melpro }}" class="form-input"></div>
                                <div><label class="form-label">Téléphone</label><input type="text" name="telpro" value="{{ $pro->telpro }}" class="form-input"></div>
                                
                                <div><label class="form-label">Code Postal</label><input type="text" name="cp" value="{{ $pro->cp }}" class="form-input"></div>
                                <div><label class="form-label">Ville</label><input type="text" name="ville" value="{{ $pro->ville }}" class="form-input"></div>
                                
                                <div class="md:col-span-2 pt-4 border-t border-slate-100">
                                    <label class="form-label mb-3">Réseaux Sociaux</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="relative"><i class="fa-brands fa-instagram absolute left-3 top-3 text-pink-500 text-lg"></i><input type="text" name="instagram" value="{{ $pro->instagram }}" placeholder="@mon_institut" class="form-input pl-10"></div>
                                        <div class="relative"><i class="fa-brands fa-tiktok absolute left-3 top-3 text-black text-lg"></i><input type="text" name="tiktok" value="{{ $pro->tiktok }}" placeholder="@mon_institut" class="form-input pl-10"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="border-t border-slate-100 pt-8">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Sécurité du compte
                            </h3>
                            
                            <div class="bg-indigo-50/50 p-6 rounded-2xl border border-indigo-100">
                                <form method="POST" action="{{ route('pro.mfa.toggle') }}">
                                    @csrf
                                    <div class="flex items-start gap-4">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-700 text-sm">Authentification à deux facteurs (2FA)</h4>
                                            <p class="text-xs text-slate-500 mt-1">
                                                Renforcez la sécurité de votre compte. Un code sera envoyé par SMS au 
                                                <span class="font-mono font-bold text-indigo-600">{{ $pro->telpro }}</span> 
                                                à chaque connexion.
                                            </p>
                                        </div>
                                        <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input type="checkbox" name="mfa_active" id="mfaSwitch" 
                                                   class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-300 checked:right-0 checked:border-indigo-600 transition-all duration-300"
                                                   {{ $pro->mfa_active ? 'checked' : '' }}
                                                   onchange="this.form.submit()"/>
                                            <label for="mfaSwitch" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-8">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-lock text-indigo-500"></i> Mot de passe
                            </h3>
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                <span style="font-size: 0.875rem;"><span style="color: red;">*</span>&nbsp;Champs obligatoires</span>
                                <form action="{{ route('pro.password.update') }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <div x-data="{ show: false }">
                                            <label for="current_password" class="form-label">Ancien mot de passe <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input :type="show ? 'text' : 'password'" name="current_password" id="current_password" class="form-input pr-10" required>
                                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 transition">
                                                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </div>
                                            @error('current_password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <div x-data="{ show: false }">
                                            <label for="password" class="form-label">Nouveau mot de passe <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <input :type="show ? 'text' : 'password'" name="password" id="password" class="form-input pr-10" required>
                                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 transition">
                                                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </div>
                                            @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe <span class="text-red-500">*</span></label>
                                        
                                        <div x-data="{ show: false }" class="relative md:w-1/2">
                                            
                                            <input 
                                                :type="show ? 'text' : 'password'" 
                                                name="password_confirmation" 
                                                id="password_confirmation" 
                                                class="form-input w-full pr-10" 
                                                required>
                                                
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 transition">
                                                <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold shadow-sm transition text-sm">
                                            Modifier le mot de passe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>

            </main>
        </div>
    </div>

    <div id="smsModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeSmsModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-lg w-full relative z-10 transform transition-all">
                <div class="bg-[#2e2e48] p-8 text-white text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold">Recharger mon compte SMS</h3>
                        <p class="text-sm text-slate-400 mt-2">Solde actuel : <span class="font-bold text-white bg-white/10 px-2 py-0.5 rounded">{{ $data['credit_sms'] }}</span></p>
                    </div>
                    <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-indigo-500 rounded-full blur-2xl opacity-50"></div>
                </div>
                <form action="{{ route('professionnel.buySmsPack') }}" method="POST" class="p-8">
                    @csrf
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        @foreach($data['sms_packs'] as $qty => $pack)
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="pack_quantity" value="{{ $qty }}" class="peer sr-only" required>
                            <div class="border-2 border-slate-100 rounded-2xl p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 group-hover:border-slate-300 transition h-full flex flex-col justify-center">
                                <p class="text-2xl font-black text-slate-800">{{ $qty }}</p>
                                <p class="text-xs font-bold text-indigo-600 uppercase mt-1 bg-indigo-100/50 py-1 rounded">{{ $pack['price'] }}€</p>
                            </div>
                            <div class="absolute top-2 right-2 hidden peer-checked:block text-indigo-500"><i class="fa-solid fa-circle-check"></i></div>
                        </label>
                        @endforeach
                    </div>
                    <button class="w-full bg-indigo-600 text-white py-3.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg mb-3">
                        Payer par Carte Bancaire
                    </button>
                    <button type="button" onclick="closeSmsModal()" class="w-full py-2 text-sm text-slate-400 hover:text-slate-600 font-medium">Annuler</button>
                </form>
            </div>
        </div>
    </div>

    <div id="editPrestationModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditPrestationModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-2xl w-full relative z-10">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-xl font-bold text-slate-800">Modifier la prestation</h3>
                    <button onclick="closeEditPrestationModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form id="formEditPrestation" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_idprestation" name="idprestation">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Nom de la prestation <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_nomprestation" name="nomprestation" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                            <select name="libellecategorie" id="edit_libellecategorie" class="form-input" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->libellecategorie }}">{{ $cat->libellecategorie }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="form-label">Durée (min) <span class="text-red-500">*</span></label>
                            <input type="number" id="edit_duree" name="duree" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Prix HT (€) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" id="edit_tarifht" name="tarifht" class="form-input" required>
                        </div>
                         <div>
                            <label class="form-label">Espèce <span class="text-red-500">*</span></label>
                             <select name="especes[]" id="edit_espece" class="form-input" required>
                                @foreach($especes as $espece)
                                    <option value="{{ $espece->idespece }}">{{ ucfirst($espece->libelleespece) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeEditPrestationModal()" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="createPrestationModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCreatePrestationModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-2xl w-full relative z-10">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-xl font-bold text-slate-800">Ajouter une prestation</h3>
                    <button onclick="closeCreatePrestationModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form action="{{ route('prestation.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Nom de la prestation <span class="text-red-500">*</span></label>
                            <input type="text" name="nomprestation" class="form-input" placeholder="Ex: Consultation" required>
                        </div>
                        <div>
                            <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                            <select name="libellecategorie" class="form-input" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->libellecategorie }}">{{ $cat->libellecategorie }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="form-label">Durée (min) <span class="text-red-500">*</span></label>
                            <input type="number" name="duree" class="form-input" placeholder="30" required>
                        </div>
                        <div>
                            <label class="form-label">Prix HT (€) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="tarifht" class="form-input" placeholder="0.00" required>
                        </div>
                         <div>
                            <label class="form-label">Espèce <span class="text-red-500">*</span></label>
                             <select name="especes[]" class="form-input" required>
                                @foreach($especes as $espece)
                                    <option value="{{ $espece->idespece }}">{{ ucfirst($espece->libelleespece) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeCreatePrestationModal()" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">Créer le service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="createAnimalModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCreateAnimalModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-2xl w-full relative z-10">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-xl font-bold text-slate-800">Ajouter un animal</h3>
                    <button onclick="closeCreateAnimalModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form action="{{ route('animal.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="nom1animal" class="form-input" placeholder="Rex" required>
                        </div>
                        <div>
                            <label class="form-label">Espèce <span class="text-red-500">*</span></label>
                            <select name="idespece" class="form-input" required>
                                @foreach($especes as $esp)
                                    <option value="{{ $esp->idespece }}">{{ ucfirst($esp->libelleespece) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Sexe</label>
                            <select name="sexe" class="form-input">
                                <option value="1">Mâle</option>
                                <option value="0">Femelle</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="datenaissance" class="form-input">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Propriétaire (Client)</label>
                        <select name="idclient" class="form-input select2-enable">
                            <option value="">-- Sélectionner --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->idclient }}">{{ $client->nom }} {{ $client->prenom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeCreateAnimalModal()" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="createRdvModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCreateRdvModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-2xl w-full relative z-10">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-xl font-bold text-slate-800">Nouveau Rendez-vous</h3>
                    <button onclick="closeCreateRdvModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form action="{{ route('rdv.client.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="daterdv" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Heure <span class="text-red-500">*</span></label>
                            <input type="time" name="heurerdv" class="form-input" value="09:00" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Client <span class="text-red-500">*</span></label>
                        <select name="idclient" id="rdv_client_select" class="form-input" onchange="filterAnimalsByClient()" required>
                            <option value="">-- Choisir le client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->idclient }}">{{ $client->nom }} {{ $client->prenom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Animal <span class="text-red-500">*</span></label>
                        <select name="numtatouage" id="rdv_animal_select" class="form-input" required disabled>
                            <option value="">-- Choisir d'abord un client --</option>
                            @foreach($animaux as $animal)
                                <option value="{{ $animal->numtatouage }}" data-client="{{ $animal->idclient }}">
                                    {{ $animal->nom1animal }} 
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Prestataire <span class="text-red-500">*</span></label>
                        <select name="idemploye" class="form-input" required>
                            <option value="">-- Choisir le prestataire --</option>
                            @if(isset($employes))
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->idemploye }}">{{ $employe->prenom }} {{ $employe->nom }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Aucun employé trouvé (Mettre à jour le Controller)</option>
                            @endif
                        </select>
                        @if(!isset($employes))
                            <p class="text-xs text-red-400 mt-1"><i class="fa-solid fa-triangle-exclamation"></i> Attention: Ajoutez la variable $employes dans votre Controller.</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Prestation <span class="text-red-500">*</span></label>
                        <select name="idprestation" class="form-input" required>
                            <option value="">-- Choisir le service --</option>
                            @foreach($prestations as $presta)
                                <option value="{{ $presta->idprestation }}">{{ $presta->nomprestation }} ({{ $presta->duree }} min - {{ $presta->tarifht }}€)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeCreateRdvModal()" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">Valider le RDV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="createProductModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeCreateProductModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-3xl w-full relative z-10 max-h-[90vh] overflow-y-auto custom-scrollbar">
                
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4 sticky top-0 bg-white z-20">
                    <h3 class="text-xl font-bold text-slate-800">Ajouter un nouveau produit</h3>
                    <button onclick="closeCreateProductModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form action="{{ route('produit.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Photo du produit <span class="text-slate-400 font-normal text-xs">(Optionnel)</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:bg-slate-50 transition relative">
                            <div class="space-y-1 text-center">
                                <i class="fa-solid fa-image text-slate-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="image_prod" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Télécharger un fichier</span>
                                        <input id="image_prod" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG jusqu'à 2Mo</p>
                            </div>
                            <img id="preview_img_prod" class="hidden absolute inset-0 w-full h-full object-contain bg-white rounded-lg p-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-1">
                            <label class="form-label">Nom du produit <span class="text-red-500">*</span></label>
                            <input type="text" name="nomproduit" class="form-input" required placeholder="Ex: Shampoing Aloe Vera">
                        </div>

                        <div>
                            <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                            <select name="libellecategoriepro" class="form-input" required>
                                <option value="">-- Choisir --</option>
                                @if(isset($categoriesProduits))
                                    @foreach($categoriesProduits as $cat)
                                        <option value="{{ $cat->libellecategoriepro }}">{{ $cat->libellecategoriepro }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Aucune catégorie trouvée</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">Espèce cible <span class="text-slate-400 font-normal text-xs">(Sera ajouté au nom)</span></label>
                            <select name="idespece" class="form-input">
                                <option value="">Toutes espèces / Général</option>
                                @foreach($especes as $esp)
                                    <option value="{{ $esp->idespece }}">{{ ucfirst($esp->libelleespece) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Stock Initial <span class="text-red-500">*</span></label>
                            <input type="number" name="stocks" class="form-input" required min="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="form-label">N° de série</label>
                            <input type="text" name="numserie" class="form-input" placeholder="Optionnel">
                        </div>
                        <div>
                            <label class="form-label">N° de lot</label>
                            <input type="text" name="numlot" class="form-input" placeholder="Optionnel">
                        </div>
                        <div>
                            <label class="form-label">Seuil d'alerte <span class="text-red-500">*</span></label>
                            <input type="number" name="seuilalerte" class="form-input" required min="0" value="5">
                        </div>
                    </div>

                    <h4 class="font-bold text-slate-700 mb-4 text-sm uppercase tracking-wide">Tarification</h4>
                    <input type="hidden" id="taux_tva_pro_modal" value="{{ $pro->tva ?? 0 }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div>
                            <label class="form-label">Achat HT (€) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="prixachat" class="form-input" required placeholder="0.00">
                        </div>
                        <div>
                            <label class="form-label">Vente HT (€) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="prixvente" id="prixvente_modal" class="form-input" required placeholder="0.00" oninput="calculateTTC()">
                        </div>
                        <div>
                            <label class="form-label text-slate-400">Prix TTC (Estimé)</label>
                            <input type="text" id="prixttc_modal" class="form-input bg-slate-100 text-slate-500 cursor-not-allowed border-slate-200" disabled placeholder="-- €">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeCreateProductModal()" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg transition">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Enregistrer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="venteModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeVenteModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full relative z-10 overflow-hidden flex flex-col max-h-[90vh]" 
                 x-data="caisseData()">
                
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-cash-register text-emerald-600"></i> Enregistrer une vente
                        </h3>
                        <p class="text-sm text-slate-500">Sélectionnez le client et les produits.</p>
                    </div>
                    <button type="button" onclick="closeVenteModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form action="{{ route('pro.vente.store') }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">
                        
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <label class="form-label mb-2"><i class="fa-solid fa-user mr-2 text-indigo-500"></i>Client</label>
                            <select name="idclient" class="form-input w-full" required>
                                <option value="">-- Sélectionner un client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->idclient }}">{{ $client->nom }} {{ $client->prenom }} ({{ $client->ville }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <label class="form-label"><i class="fa-solid fa-box mr-2 text-indigo-500"></i>Panier</label>
                                <button type="button" @click="addLine()" class="text-xs font-bold text-indigo-600 hover:underline">+ Ajouter une ligne</button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(line, index) in lines" :key="index">
                                    <div class="flex gap-2 items-start">
                                        <div class="flex-1">
                                            <select :name="'produits['+index+'][id]'" x-model="line.id" @change="updatePrice(index)" class="form-input text-sm" required>
                                                <option value="">Choisir produit...</option>
                                                @if(isset($produits))
                                                    @foreach($produits as $prod)
                                                        <option value="{{ $prod->idproduit }}" 
                                                                data-price="{{ $prod->prixvente }}" 
                                                                data-stock="{{ $prod->stocks }}">
                                                            {{ $prod->nomproduit }} ({{ number_format($prod->prixvente, 2) }}€) - Stock: {{ $prod->stocks }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="w-20">
                                            <input type="number" :name="'produits['+index+'][qty]'" x-model="line.qty" min="1" class="form-input text-center text-sm font-bold" required placeholder="Qté">
                                        </div>

                                        <div class="w-24 pt-2 text-right font-mono text-sm text-slate-600">
                                            <span x-text="(line.price * line.qty).toFixed(2)"></span> €
                                        </div>

                                        <button type="button" @click="removeLine(index)" class="pt-2 text-red-400 hover:text-red-600 px-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 border-dashed mt-4">
                            <div class="flex items-end gap-3">
                                <div class="flex-1">
                                    <label class="form-label text-xs">Code Promo (Optionnel)</label>
                                    <input type="text" x-model="promoCodeInput" name="code_promo" class="form-input text-sm uppercase font-mono" placeholder="Ex: ETE2024">
                                </div>
                                <button type="button" @click="checkPromo()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition shadow-sm">
                                    Appliquer
                                </button>
                            </div>
                            <p class="text-xs mt-2 font-bold" 
                               :class="promoValid ? 'text-green-600' : 'text-red-500'" 
                               x-text="promoMessage"></p>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                            <span class="text-sm font-bold text-slate-500">NET À PAYER</span>
                            <div class="text-right">
                                <div x-show="discountAmount > 0" class="text-xs text-green-600 font-medium mb-1">
                                    Remise : -<span x-text="discountAmount.toFixed(2)"></span> €
                                </div>
                                <p class="text-3xl font-black text-emerald-600"><span x-text="calculateTotal()"></span> €</p>
                            </div>
                        </div>

                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" onclick="closeVenteModal()" class="px-6 py-3 text-slate-500 font-bold hover:bg-white rounded-xl transition border border-transparent hover:border-slate-200">Annuler</button>
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-700 shadow-lg flex items-center gap-2">
                            <i class="fa-solid fa-check"></i> Valider et Facturer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editFactureModal" class="fixed inset-0 z-50 hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditFactureModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-lg w-full relative z-10">
                <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Modifier l'en-tête</h3>
                        <p class="text-xs text-slate-500">Facture #<span id="edit_facture_id_display"></span></p>
                    </div>
                    <button onclick="closeEditFactureModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>

                <form id="formEditFacture" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Titre / Nature de la prestation</label>
                            <input type="text" id="edit_facture_nature" name="nature" class="form-input" placeholder="Ex: Prestation Vétérinaire">
                            <p class="text-[10px] text-slate-400 mt-1">Apparaît dans le tableau de la facture.</p>
                        </div>

                        <div>
                            <label class="form-label">Nom de l'émetteur (Optionnel)</label>
                            <input type="text" id="edit_facture_emetteur" name="custom_emetteur" class="form-input" placeholder="Laisser vide pour utiliser : {{ $pro->libelleetablissement }}">
                            <p class="text-[10px] text-slate-400 mt-1">Surcharge le nom de l'établissement pour cette facture uniquement.</p>
                        </div>

                        <div>
                            <label class="form-label">Logo spécifique (Optionnel)</label>
                            <input type="file" name="custom_logo" class="form-input text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                            <p class="text-[10px] text-slate-400 mt-1">Surcharge le logo par défaut pour cette facture uniquement.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeEditFactureModal()" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-50 rounded-lg">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 shadow-lg">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="rdvActionModal" class="fixed inset-0 z-[60] hidden" style="display: none;">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="closeRdvActionModal()"></div>
    
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-50">
            
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center border-b pb-3 mb-3">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Détails du Rendez-vous</h3>
                    <button type="button" onclick="closeRdvActionModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>

                <div id="modal_loading" class="text-center py-4">Chargement...</div>

                <div id="modal_content" class="hidden">
                    <div class="text-sm text-gray-600 mb-6 bg-gray-50 p-4 rounded">
                        <p><strong>Client :</strong> <span id="m_client"></span></p>
                        <p><strong>Animal :</strong> <span id="m_animal"></span></p>
                        <p><strong>Date :</strong> <span id="m_date"></span></p>
                        <p><strong>Prestation :</strong> <span id="m_presta"></span></p>
                        <p><strong>Prix théorique :</strong> <span id="m_total"></span> €</p>
                    </div>

                    <form id="rdvActionForm" method="POST" action="">
                        @csrf
                        
                        <div id="opt_facture" class="hidden text-center space-y-3">
                            <div class="p-2 bg-green-100 text-green-800 rounded font-bold text-sm">Traîté / Facturé</div>
                            <a id="btn_facture" href="#" class="inline-block w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Voir la facture
                            </a>
                        </div>

                        <div id="opt_past" class="hidden space-y-3">
                            <p class="text-xs font-bold text-gray-500 uppercase text-center">Rendez-vous terminé</p>
                            <button type="submit" name="action" value="validate" class="w-full rounded-md bg-green-600 px-3 py-3 text-sm font-bold text-white shadow-sm hover:bg-green-500">
                                Client présent (Valider & Facturer)
                            </button>
                            <button type="button" onclick="showCancelMenu()" class="w-full rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                Client absent
                            </button>
                        </div>

                        <div id="opt_future" class="hidden space-y-3">
                            <p class="text-xs font-bold text-gray-500 uppercase text-center">Gestion Annulation</p>
                            <button type="submit" name="action" value="cancel_company" onclick="return confirm('Confirmer l\'annulation société ?')" class="w-full rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                                Annulation Société (Interne)
                            </button>
                            <button type="button" onclick="showCancelMenu()" class="w-full rounded-md bg-white px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                Annulation Client
                            </button>
                        </div>

                        <div id="opt_cancel_sub" class="hidden mt-4 bg-red-50 p-3 rounded border border-red-100">
                            <label class="flex items-center gap-2 mb-4 cursor-pointer">
                                <input type="checkbox" name="is_late_cancellation" value="1" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-600">
                                <span class="text-sm font-medium text-gray-900">Annulation tardive (< 24h) ?</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-3 ml-6">Si coché : Facture de pénalité (30%).</p>
                            
                            <div class="flex gap-2">
                                <button type="button" onclick="hideCancelMenu()" class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Retour</button>
                                <button type="submit" name="action" value="cancel_client" class="flex-1 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Confirmer</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openRdvActionModal(id) {
        const modal = document.getElementById('rdvActionModal');
        modal.classList.remove('hidden');
        modal.style.display = 'block'; 
        
        document.getElementById('modal_loading').classList.remove('hidden');
        document.getElementById('modal_content').classList.add('hidden');

        ['opt_facture', 'opt_past', 'opt_future', 'opt_cancel_sub'].forEach(el => {
            document.getElementById(el).classList.add('hidden');
        });

        fetch(`{{ url('/rdv/details') }}/${id}`)
            .then(response => {
                if (!response.ok) throw new Error("Erreur réseau");
                return response.json();
            })
            .then(data => {
                console.log("Données reçues :", data); 
                document.getElementById('m_client').textContent = data.client;
                document.getElementById('m_animal').textContent = data.animal;
                document.getElementById('m_date').textContent = data.date + ' ' + data.heure;
                document.getElementById('m_presta').textContent = data.prestations;
                document.getElementById('m_total').textContent = data.total;
                
                document.getElementById('rdvActionForm').action = `/rdv/traiter/${data.id}`;

                
                if (data.has_facture) {
                    document.getElementById('opt_facture').classList.remove('hidden');
                    document.getElementById('btn_facture').href = `/facture/${data.facture_id}/download`; 
                } 
                else if (data.statut_id == 3) {
                    document.getElementById('modal_content').innerHTML = "<div class='p-4 text-center text-red-600 font-bold'>Ce rendez-vous est annulé.</div>";
                }
                else if (data.is_past) {
                    document.getElementById('opt_past').classList.remove('hidden');
                } 
                else {
                    document.getElementById('opt_future').classList.remove('hidden');
                }

                document.getElementById('modal_loading').classList.add('hidden');
                document.getElementById('modal_content').classList.remove('hidden');
            })
            .catch(err => {
                console.error(err);
                alert("Impossible de charger le RDV.");
                closeRdvActionModal();
            });
    }

    function closeRdvActionModal() {
        const modal = document.getElementById('rdvActionModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    function showCancelMenu() {
        document.getElementById('opt_past').classList.add('hidden');
        document.getElementById('opt_future').classList.add('hidden');
        document.getElementById('opt_cancel_sub').classList.remove('hidden');
    }

    function hideCancelMenu() {
        document.getElementById('opt_cancel_sub').classList.add('hidden');
        
        closeRdvActionModal();
    }
        function openEditFactureModal(facture) {
            const form = document.getElementById('formEditFacture');
            form.action = `/facture/${facture.idfacture}/update`;

            document.getElementById('edit_facture_id_display').textContent = facture.idfacture;
            document.getElementById('edit_facture_nature').value = facture.nature || '';
            document.getElementById('edit_facture_emetteur').value = facture.custom_emetteur || '';
            
            document.getElementById('editFactureModal').classList.remove('hidden');
        }

        function closeEditFactureModal() {
            document.getElementById('editFactureModal').classList.add('hidden');
        }

        const basePrixMensuel = {{ $data['prix_base_mensuel'] ?? 0 }};
        const basePrixAnnuel = {{ $data['prix_base_annuel'] ?? 0 }};
        
        function filterFactures() {
            const nameInput = document.getElementById('searchFactureClient').value.toUpperCase();
            const dateInput = document.getElementById('searchFactureDate').value;
            const table = document.getElementById('tableFactures');
            const rows = table.getElementsByClassName('facture-row');

            for (let i = 0; i < rows.length; i++) {
                const clientCell = rows[i].querySelector('.facture-client').textContent.toUpperCase();
                const dateCell = rows[i].querySelector('.facture-date').getAttribute('data-date');
                
                let showByName = clientCell.includes(nameInput);
                let showByDate = dateInput === "" || dateCell === dateInput;

                if (showByName && showByDate) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        function resetFilters() {
            document.getElementById('searchFactureClient').value = "";
            document.getElementById('searchFactureDate').value = "";
            filterFactures();
        }
        
        function updateTotal() {
            const toggle = document.getElementById('subscriptionToggle');
            if(!toggle) return;
            
            const isAnnuel = toggle.checked;
            let currentBase = isAnnuel ? basePrixAnnuel : basePrixMensuel;
            let optionsTotal = 0;
            
            document.querySelectorAll('input[name="modules[]"]:checked').forEach(cb => {
                let p = parseFloat(cb.getAttribute('data-price'));
                if (!isNaN(p)) optionsTotal += isAnnuel ? (p * 12) : p;
            });
            
            let totalHT = currentBase + optionsTotal;
            let totalTTC = totalHT * 1.20;
            
            if(document.getElementById('totalPriceDisplay')) document.getElementById('totalPriceDisplay').innerText = totalHT.toFixed(2) + " €";
            if(document.getElementById('headerTotal')) document.getElementById('headerTotal').innerText = totalTTC.toFixed(2) + " €";
            if(document.getElementById('periodDisplay')) document.getElementById('periodDisplay').innerText = isAnnuel ? "HT / an" : "HT / mois";
            if(document.getElementById('modalTotalAmount')) document.getElementById('modalTotalAmount').innerText = totalTTC.toFixed(2) + " € TTC";
        }

        function openPaymentModal() { document.getElementById('paymentModal').classList.remove('hidden'); }
        function closePaymentModal() { document.getElementById('paymentModal').classList.add('hidden'); }
        function openSmsModal() { document.getElementById('smsModal').classList.remove('hidden'); }
        function closeSmsModal() { document.getElementById('smsModal').classList.add('hidden'); }

        function openCreatePrestationModal() { document.getElementById('createPrestationModal').classList.remove('hidden'); }
        function closeCreatePrestationModal() { document.getElementById('createPrestationModal').classList.add('hidden'); }
        
        function openCreateAnimalModal() { document.getElementById('createAnimalModal').classList.remove('hidden'); }
        function closeCreateAnimalModal() { document.getElementById('createAnimalModal').classList.add('hidden'); }
        
        function openCreateRdvModal() { document.getElementById('createRdvModal').classList.remove('hidden'); }
        function closeCreateRdvModal() { document.getElementById('createRdvModal').classList.add('hidden'); }

        function openCreateProductModal() {
            document.getElementById('createProductModal').classList.remove('hidden');
        }

        function closeCreateProductModal() {
            document.getElementById('createProductModal').classList.add('hidden');
        }

        function calculateTTC() {
            let htInput = document.getElementById('prixvente_modal');
            let ht = parseFloat(htInput.value);
            let taux = parseFloat(document.getElementById('taux_tva_pro_modal').value);
            let inputTTC = document.getElementById('prixttc_modal');

            if (inputTTC && !isNaN(ht)) {
                let ttc = ht * (1 + taux);
                inputTTC.value = ttc.toFixed(2) + ' €';
            } else if (inputTTC) {
                inputTTC.value = '';
            }
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview_img_prod');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function openVenteModal() { document.getElementById('venteModal').classList.remove('hidden'); }
        function closeVenteModal() { document.getElementById('venteModal').classList.add('hidden'); }

    function caisseData() {
        return {
            lines: [{ id: '', qty: 1, price: 0 }],
            promoCodeInput: '',
            promoValid: false,
            promoMessage: '',
            discountType: null,
            discountValue: 0,
            discountAmount: 0,
            
            addLine() {
                this.lines.push({ id: '', qty: 1, price: 0 });
            },
            
            removeLine(index) {
                if (this.lines.length > 1) {
                    this.lines.splice(index, 1);
                } else {
                    this.lines[0].id = ''; this.lines[0].price = 0; this.lines[0].qty = 1;
                }
            },
            
            updatePrice(index) {
                setTimeout(() => {
                    const selects = document.querySelectorAll('#venteModal select[name^="produits"]');
                    const select = selects[index];
                    if(select && select.selectedOptions.length > 0) {
                        const option = select.selectedOptions[0];
                        this.lines[index].price = parseFloat(option.getAttribute('data-price')) || 0;
                    }
                }, 50);
            },
            
            async checkPromo() {
                if(!this.promoCodeInput) return;
                
                try {
                    const response = await fetch('{{ route("pro.vente.check-promo") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ code: this.promoCodeInput })
                    });
                    
                    const data = await response.json();
                    
                    if(data.valid) {
                        this.promoValid = true;
                        this.promoMessage = data.message + (data.type === 'pourcentage' ? ' (-' + data.valeur + '%)' : ' (-' + data.valeur + '€)');
                        this.discountType = data.type;
                        this.discountValue = parseFloat(data.valeur);
                    } else {
                        this.promoValid = false;
                        this.promoMessage = data.message;
                        this.discountType = null;
                        this.discountValue = 0;
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    this.promoValid = false;
                    this.promoMessage = "Erreur de vérification.";
                }
            },
            
            calculateTotal() {
                let subTotal = 0;
                this.lines.forEach(line => { subTotal += (line.price * line.qty); });
                
                this.discountAmount = 0;
                if(this.promoValid) {
                    if(this.discountType === 'pourcentage') {
                        this.discountAmount = subTotal * (this.discountValue / 100);
                    } else {
                        this.discountAmount = this.discountValue;
                    }
                }
                
                let total = Math.max(0, subTotal - this.discountAmount);
                return total.toFixed(2);
            }
        }
    }
        
        function filterAnimalsByClient() {
            const clientId = document.getElementById('rdv_client_select').value;
            const animalSelect = document.getElementById('rdv_animal_select');
            const options = animalSelect.querySelectorAll('option');
            
            animalSelect.value = "";
            animalSelect.disabled = (clientId === "");

            options.forEach(opt => {
                if(opt.value === "") return;
                if(opt.getAttribute('data-client') == clientId) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
        }
        
        function openEditPrestationModal(data) {
            document.getElementById('edit_idprestation').value = data.idprestation;
            document.getElementById('edit_nomprestation').value = data.nomprestation;
            document.getElementById('edit_duree').value = data.duree;
            document.getElementById('edit_tarifht').value = data.tarifht;
            
            const catSelect = document.getElementById('edit_libellecategorie');
            if(catSelect) catSelect.value = data.libellecategorie;
            
            const espSelect = document.getElementById('edit_espece');
            if(espSelect && data.idespece) espSelect.value = data.idespece;

            let form = document.getElementById('formEditPrestation');
            form.action = "/prestation/" + data.idprestation; 

            document.getElementById('editPrestationModal').classList.remove('hidden');
        }

        function closeEditPrestationModal() {
            document.getElementById('editPrestationModal').classList.add('hidden');
        }

        window.onload = updateTotal;
    </script>
    @if($pro->statut != 1)
        <div class="fixed inset-0 z-[100] bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-4" x-data>
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-lg w-full text-center relative overflow-hidden border border-slate-200">
                
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                @if($pro->statut == 2)
                    <div class="mb-6 inline-flex p-4 rounded-full bg-amber-100 text-amber-600 mb-4 animate-pulse">
                        <i class="fa-solid fa-hourglass-half text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Dossier en cours d'analyse</h2>
                    <p class="text-slate-500 mb-8 leading-relaxed">
                        Bienvenue <strong>{{ $pro->prenompro }}</strong> !<br>
                        Votre compte a été créé avec succès. Nos équipes administratives vérifient actuellement vos informations (SIRET, identité).
                        <br><br>
                        <span class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600">Vous recevrez un email dès la validation.</span>
                    </p>
                @elseif($pro->statut == 3)
                    <div class="mb-6 inline-flex p-4 rounded-full bg-red-100 text-red-600 mb-4">
                        <i class="fa-solid fa-ban text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Inscription Refusée</h2>
                    <p class="text-slate-500 mb-8 leading-relaxed">
                        Après étude de votre dossier, nous ne pouvons malheureusement pas valider votre inscription sur Hunimalis.
                        <br>
                        Veuillez contacter le support pour plus d'informations.
                    </p>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2 group">
                        <i class="fa-solid fa-power-off group-hover:text-red-400 transition"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
        
        <script>
            document.body.style.overflow = 'hidden'; // Bloque le défilement
        </script>
    @endif
</body>
</html>