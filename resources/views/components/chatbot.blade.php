<div x-data='chatBot({{ Auth::check() && Auth::user()->personne ? json_encode(Auth::user()->personne->prenom) : "null" }})' 
     x-init="initChat()" 
     @open-chat.window="isOpen = true" 
     class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end gap-4 font-sans print:hidden">

    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="bg-white w-80 sm:w-96 h-[500px] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-100"
         style="display: none;">
        
        <div class="bg-[#1e293b] p-4 flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <h3 class="font-bold text-sm">Assistant Hunimalis</h3>
            </div>
            <div class="flex gap-3">
                <button @click="clearHistory()" type="button" title="Effacer la conversation" class="text-gray-400 hover:text-red-400 transition">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
                <button @click="isOpen = false" type="button" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4" x-ref="scrollContainer">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.sender === 'user' 
                        ? 'bg-[#1e293b] text-white rounded-tl-xl rounded-tr-xl rounded-bl-xl' 
                        : 'bg-white border border-gray-200 text-gray-700 rounded-tr-xl rounded-br-xl rounded-bl-xl'"
                        class="p-3 text-sm shadow-sm max-w-[85%]">
                        <div x-html="msg.text" class="leading-relaxed"></div>
                    </div>
                </div>
            </template>
            
            <div x-show="isLoading" class="flex justify-start">
                <div class="bg-gray-200 text-gray-500 rounded-xl p-3 text-xs flex gap-1 items-center">
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                </div>
            </div>
        </div>

        <div class="p-3 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input x-model="userInput" 
                    type="text" 
                    placeholder="Posez votre question..." 
                    class="flex-1 bg-gray-100 text-sm rounded-full px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500/50 transition text-gray-800"
                    :disabled="isLoading">
                
                <button type="submit" 
                        class="w-10 h-10 bg-[#1e293b] text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isLoading || userInput.trim() === ''">
                    <i class="fa-solid fa-paper-plane text-sm"></i>
                </button>
            </form>
        </div>
    </div>

    <button @click="isOpen = !isOpen" 
            type="button"
            class="bg-[#1e293b] hover:bg-[#0f172a] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110 active:scale-95 group">
        <i x-show="!isOpen" class="fa-solid fa-comments text-2xl group-hover:animate-bounce"></i>
        <i x-show="isOpen" class="fa-solid fa-chevron-down text-xl" style="display: none;"></i>
    </button>
</div>

<script>
    window.chatBot = function(userName) {
        
        let displayName = "visiteur";
        if (userName && userName !== 'null' && userName !== null && String(userName).trim() !== '') {
            displayName = userName;
        }

        const defaultText = `Bonjour ${displayName} ! Je suis l'IA Hunimalis 🐶. Comment puis-je vous aider à trouver un professionnel ou un produit aujourd'hui ?`;

        return {
            isOpen: false,
            userInput: '',
            isLoading: false,
            messages: JSON.parse(localStorage.getItem('hunimalis_chat_history')) || [
                { text: defaultText, sender: 'bot' }
            ],

            initChat() {
                if (this.messages.length > 0 && this.messages[0].sender === 'bot') {
                    this.messages[0].text = defaultText;
                    this.saveHistory(); 
                }

                this.$watch('isOpen', value => {
                    if (value) {
                        this.scrollToBottom();
                    }
                });
            },

            saveHistory() {
                localStorage.setItem('hunimalis_chat_history', JSON.stringify(this.messages));
            },

            clearHistory() {
                this.messages = [{ text: defaultText, sender: 'bot' }];
                this.saveHistory();
                this.scrollToBottom();
            },

            sendMessage() {
                if (this.userInput.trim() === '') return;
                
                const text = this.userInput;
                this.messages.push({ text: text, sender: 'user' });
                this.userInput = '';
                this.isLoading = true;
                this.saveHistory(); 
                
                this.scrollToBottom();

                let recentHistory = this.messages.slice(-6, -1);

                fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        message: text,
                        user_name: displayName,
                        history: recentHistory
                    })
                })
                .then(response => response.json())
                .then(data => {
                    this.messages.push({ text: data.reply, sender: 'bot' });
                    this.saveHistory(); 
                    this.scrollToBottom();
                })
                .catch(error => {
                    console.error('Erreur Chat:', error);
                    this.messages.push({ text: "Erreur de connexion.", sender: 'bot' });
                    this.scrollToBottom();
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            scrollToBottom() {
                const performScroll = () => {
                    const box = this.$refs.scrollContainer;
                    if (box) {
                        box.scrollTop = box.scrollHeight;
                    }
                };

                
                this.$nextTick(() => performScroll());

                setTimeout(() => performScroll(), 50);

                setTimeout(() => performScroll(), 350);
            }
        }
    }
</script>