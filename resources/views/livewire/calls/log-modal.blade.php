<div>
    <!-- Button Trigger (Optional, if not triggered from parent) -->
    
    <!-- Modal Backdrop -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <!-- Modal Content -->
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[95vh]" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <!-- Header -->
            <div class="px-8 py-6 flex items-center justify-between border-b border-slate-50 flex-shrink-0">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Registar <span class="text-indigo-600">Nueva Llamada</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Completa los detalles de la interacción</p>
                </div>
                <button wire:click="$set('showModal', false)" class="p-2 bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar">
                @if (session()->has('error'))
                    <div class="px-5 py-4 bg-red-50 border border-red-100 text-red-600 text-xs font-bold rounded-2xl flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="save" id="callLogForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Tipo de Llamada</label>
                            <div class="relative">
                                <select wire:model="type" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 border-transparent rounded-[1.25rem] text-sm font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/20 transition-all appearance-none cursor-pointer">
                                    <option value="outbound">Llamada de Salida</option>
                                    <option value="inbound">Llamada de Entrada</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('type') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Result -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Resultado</label>
                            <div class="relative">
                                <select wire:model="result" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 border-transparent rounded-[1.25rem] text-sm font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/20 transition-all appearance-none cursor-pointer">
                                    <option value="pending">Pendiente / Sin Respuesta</option>
                                    <option value="interested">Interesado / Prospecto</option>
                                    <option value="not_interested">No Interesado / Descarte</option>
                                    <option value="closed">Cerrado / Ganado</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('result') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Called At -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Fecha y Hora</label>
                            <input type="datetime-local" wire:model="called_at" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-[1.25rem] text-sm font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/20 transition-all">
                            @error('called_at') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Duración (Minutos)</label>
                            <div class="relative">
                                <input type="number" wire:model="duration" placeholder="0" class="w-full pl-5 pr-14 py-3.5 bg-slate-50 border-transparent rounded-[1.25rem] text-sm font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/20 transition-all">
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none">
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-tighter">MIN</span>
                                </div>
                            </div>
                            @error('duration') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Observations -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5 ml-1">Observaciones Detalladas</label>
                        <textarea wire:model="observations" rows="3" placeholder="Resume lo acordado en la llamada..." class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-[1.5rem] text-sm font-medium text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/20 transition-all"></textarea>
                    </div>

                    <!-- Recording Section (Integrated in logic above, reused here) -->
                    <div x-data="{
                        isRecording: false,
                        mediaRecorder: null,
                        audioChunks: [],
                        seconds: 0,
                        timer: null,
                        recordingUrl: null,
                        isUploading: false,
                        recordingId: @entangle('recordingId'),

                        async startRecording() {
                            try {
                                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                                this.mediaRecorder = new MediaRecorder(stream);
                                this.audioChunks = [];

                                this.mediaRecorder.ondataavailable = (event) => {
                                    if (event.data.size > 0) {
                                        this.audioChunks.push(event.data);
                                    }
                                };

                                this.mediaRecorder.onstop = async () => {
                                    const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                                    this.recordingUrl = URL.createObjectURL(audioBlob);
                                    await this.uploadAudio(audioBlob);
                                };

                                this.mediaRecorder.start();
                                this.isRecording = true;
                                this.seconds = 0;
                                this.timer = setInterval(() => {
                                    this.seconds++;
                                }, 1000);
                            } catch (err) {
                                alert('No se pudo acceder al micrófono: ' + err.message);
                            }
                        },

                        stopRecording() {
                            if (this.mediaRecorder && this.isRecording) {
                                this.mediaRecorder.stop();
                                this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
                                this.isRecording = false;
                                clearInterval(this.timer);
                            }
                        },

                        async uploadAudio(blob) {
                            this.isUploading = true;
                            const formData = new FormData();
                            formData.append('audio', blob, 'recording.webm');
                            formData.append('duration', this.formatTime(this.seconds));

                            try {
                                const response = await fetch('/call-recordings', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                                    }
                                });

                                if (response.ok) {
                                    const data = await response.json();
                                    this.recordingId = data.id;
                                } else {
                                    alert('Error al subir la grabación');
                                }
                            } catch (err) {
                                console.error('Upload error:', err);
                                alert('Error al conectar con el servidor');
                            } finally {
                                this.isUploading = false;
                            }
                        },

                        formatTime(sec) {
                            const m = Math.floor(sec / 60);
                            const s = sec % 60;
                            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                        },

                        resetRecording() {
                            this.recordingId = null;
                            this.recordingUrl = null;
                            this.seconds = 0;
                        }
                    }" class="bg-indigo-50/30 p-6 rounded-[2rem] border border-indigo-100/30 space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center">
                                <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/><path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/></svg>
                                Grabación de llamada
                            </label>
                            <template x-if="isRecording">
                                <div class="flex items-center space-x-2 bg-red-100 px-3 py-1 rounded-full">
                                    <span class="flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                    <span class="text-[9px] font-black text-red-600 uppercase tracking-tighter">Grabando...</span>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center justify-between bg-white p-5 rounded-2xl shadow-sm border border-indigo-50">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-indigo-50 rounded-2xl">
                                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                </div>
                                <div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tight" x-text="isRecording ? 'Capturando audio' : (recordingId ? 'Grabación finalizada' : 'Listo para grabar')"></div>
                                    <div class="text-2xl font-black text-slate-900 font-mono tracking-wider tabular-nums" x-text="formatTime(seconds)">00:00</div>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <template x-if="!isRecording && !recordingId">
                                    <button type="button" @click="startRecording()" class="w-14 h-14 bg-indigo-600 text-white rounded-full flex items-center justify-center hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 group transform active:scale-95">
                                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                    </button>
                                </template>

                                <template x-if="isRecording">
                                    <button type="button" @click="stopRecording()" class="w-14 h-14 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-all shadow-xl shadow-red-200 group animate-pulse transform active:scale-95">
                                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H10a1 1 0 01-1-1v-4z"/></svg>
                                    </button>
                                </template>

                                <template x-if="recordingId && !isRecording">
                                    <button type="button" @click="resetRecording()" class="w-14 h-14 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200 transition-all transform active:scale-95">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <template x-if="recordingUrl">
                            <div class="px-2">
                                <audio :src="recordingUrl" controls class="w-full h-10 rounded-2xl outline-none shadow-sm"></audio>
                            </div>
                        </template>

                        <div x-show="isUploading" class="flex items-center justify-center space-x-3 bg-white/50 py-3 rounded-2xl border border-indigo-50/50">
                            <div class="animate-spin rounded-full h-4 w-4 border-2 border-indigo-600 border-t-transparent"></div>
                            <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Sincronizando audio...</span>
                        </div>
                    </div>

                    <!-- Next Call -->
                    <div class="bg-amber-50/30 p-6 rounded-[2rem] border border-amber-100/30 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 bg-white rounded-xl shadow-sm border border-amber-50">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-black text-slate-900 tracking-tight block">¿Agendar Seguimiento?</span>
                                    <span class="text-[9px] font-bold text-amber-400 uppercase tracking-widest leading-none">Recordatorio automático</span>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="hasNextCall" class="sr-only peer">
                                <div class="w-12 h-6.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-amber-500 shadow-inner"></div>
                            </label>
                        </div>

                        @if($hasNextCall)
                        <div x-show="$wire.hasNextCall" 
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 -translate-y-4" 
                             x-transition:enter-end="opacity-100 translate-y-0" 
                             class="pt-2 space-y-4">
                            <div>
                                <label class="block text-[9px] font-black text-amber-500 uppercase tracking-widest mb-2.5 ml-1">Fecha y Hora de Re-llamada</label>
                                <input type="datetime-local" wire:model="next_call_at" class="w-full px-5 py-3.5 bg-white border-transparent rounded-[1.25rem] text-sm font-bold text-slate-900 shadow-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500/20 transition-all">
                                @error('next_call_at') <span class="text-[10px] text-rose-500 font-bold ml-1 mt-1 block">{{ $message }}</span> @enderror
                                <p class="mt-3 text-[9px] text-slate-400 font-medium italic">* Se enviará una notificación al agente responsable.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex space-x-4 flex-shrink-0">
                <button type="button" wire:click="$set('showModal', false)" class="flex-1 px-8 py-4 bg-white border-2 border-slate-200 rounded-[1.5rem] text-sm font-black text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all transform active:scale-95 shadow-sm">
                    Descartar
                </button>
                <button type="submit" form="callLogForm" class="flex-[2] px-10 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-[1.5rem] shadow-xl shadow-indigo-200 text-sm font-black uppercase tracking-widest hover:brightness-105 transition-all transform active:scale-95 relative overflow-hidden group">
                    <span class="relative z-10">Guardar Registro</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
