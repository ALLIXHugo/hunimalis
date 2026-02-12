<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Planning professionnel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .message-erreur {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden">
        
        <h3 class="text-xl font-bold p-4 bg-gray-100 text-gray-700">Horaires de l'établissement</h3>
        
        <div class="p-4">
            
            <div class="grid grid-cols-7 gap-1 text-sm font-semibold text-gray-500 border-b pb-2 mb-2 sticky top-0 bg-white">
                <div class="col-span-1 pl-2">Jour</div>
                <div class="col-span-1 text-center">Ouverture matinée</div>
                <div class="col-span-1 text-center">Fermeture matinée</div>
                <div class="col-span-1 text-center">Ouverture après-midi</div>
                <div class="col-span-1 text-center">Fermeture après-midi</div>
                <div class="col-span-2 text-center">Action</div>
            </div>

            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2 border-b">
                @csrf 
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Lundi</p>
                <input type="hidden" name="jourD" value="Lundi">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>
            
            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2 border-b">
                @csrf
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Mardi</p>
                <input type="hidden" name="jourD" value="Mardi">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>
            
            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2 border-b">
                @csrf
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Mercredi</p>
                <input type="hidden" name="jourD" value="Mercredi">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>

            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2 border-b">
                @csrf
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Jeudi</p>
                <input type="hidden" name="jourD" value="Jeudi">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>

            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2 border-b">
                @csrf
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Vendredi</p>
                <input type="hidden" name="jourD" value="Vendredi">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>

            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2 border-b">
                @csrf
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Samedi</p>
                <input type="hidden" name="jourD" value="Samedi">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>

            <form action="{{ route('planning-pro.store') }}" method="POST" class="form-verif grid grid-cols-7 gap-1 items-start py-2">
                @csrf
                <p class="font-medium text-gray-700 col-span-1 pl-2 pt-2">Dimanche</p>
                <input type="hidden" name="jourD" value="Dimanche">
                
                <input type="time" name="ouverture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_matin" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="ouverture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                <input type="time" name="fermeture_aprem" class="col-span-1 w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                
                <div class="col-span-2 flex flex-col items-center justify-start gap-1">
                    <button type="submit" class="w-full max-w-[120px] bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        Enregistrer
                    </button>
                    <div class="message-erreur text-xs text-center"></div>
                </div>
            </form>

        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const forms = document.querySelectorAll('.form-verif');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    const ouvMatin = form.querySelector('input[name="ouverture_matin"]').value;
                    const fermMatin = form.querySelector('input[name="fermeture_matin"]').value;
                    const ouvAprem = form.querySelector('input[name="ouverture_aprem"]').value;
                    const fermAprem = form.querySelector('input[name="fermeture_aprem"]').value;
                    const errorDiv = form.querySelector('.message-erreur');
                    errorDiv.textContent = ""; 
                    let messageErreur = "";
                    let estValide = true;
                    if (ouvMatin && fermMatin && fermMatin <= ouvMatin) {
                        messageErreur += "• Matin : L'heure de fermeture doit être après l'ouverture.\n";
                        estValide = false;
                    }
                    if (ouvAprem && fermAprem && fermAprem <= ouvAprem) {
                        messageErreur += "• Après-midi : L'heure de fermeture doit être après l'ouverture.\n";
                        estValide = false;
                    }
                    if (fermMatin && ouvAprem && ouvAprem <= fermMatin) {
                        messageErreur += "• Incohérence : L'après-midi doit commencer APRES la fin de la matinée.\n";
                        estValide = false;
                    }
                    if (!estValide) {
                        event.preventDefault();
                        errorDiv.innerText = messageErreur; 
                    }
                });
            });
        });

    </script>
    
</body>
</html>