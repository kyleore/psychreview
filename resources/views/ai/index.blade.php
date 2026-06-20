@extends('layouts.app')
@section('title', 'AI Tutor')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12" x-data="aiTutor()">
    <div class="animate-fade-up text-center">
        <span class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-600 to-violet-600 px-4 py-1.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30">
            <i data-lucide="sparkles" class="h-4 w-4"></i> AI Study Tutor
        </span>
        <h1 class="mt-5 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Ask me anything, and I'll explain it clearly</h1>
        <p class="mx-auto mt-3 max-w-2xl text-slate-500">Pick a topic, choose a mode, or type your own question. The AI Tutor explains psychology concepts in clear, simple English to help you master your board exam.</p>
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-3">
        <!-- Controls -->
        <div class="lg:col-span-1">
            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
                <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><i data-lucide="book-open" class="h-4 w-4 text-brand-600"></i> Topic (optional)</label>
                <select x-model="topicId" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                    <option value="">— No specific topic —</option>
                    @foreach($topics as $t)
                        <option value="{{ $t->id }}">{{ $t->title }} ({{ $t->category->name }})</option>
                    @endforeach
                </select>

                <p class="mt-5 flex items-center gap-2 text-sm font-bold text-slate-700"><i data-lucide="wand-2" class="h-4 w-4 text-violet-600"></i> Quick modes</p>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    <button @click="ask('explain')" :disabled="loading" class="btn-press rounded-xl bg-brand-50 px-3 py-2.5 text-xs font-bold text-brand-700 ring-1 ring-brand-100 hover:bg-brand-100 disabled:opacity-50"><i data-lucide="graduation-cap" class="mx-auto mb-1 h-4 w-4"></i>Explain</button>
                    <button @click="ask('simplify')" :disabled="loading" class="btn-press rounded-xl bg-violet-50 px-3 py-2.5 text-xs font-bold text-violet-700 ring-1 ring-violet-100 hover:bg-violet-100 disabled:opacity-50"><i data-lucide="baby" class="mx-auto mb-1 h-4 w-4"></i>Simplify</button>
                    <button @click="ask('example')" :disabled="loading" class="btn-press rounded-xl bg-emerald-50 px-3 py-2.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-100 hover:bg-emerald-100 disabled:opacity-50"><i data-lucide="lightbulb" class="mx-auto mb-1 h-4 w-4"></i>Examples</button>
                    <button @click="ask('quiz')" :disabled="loading" class="btn-press rounded-xl bg-amber-50 px-3 py-2.5 text-xs font-bold text-amber-700 ring-1 ring-amber-100 hover:bg-amber-100 disabled:opacity-50"><i data-lucide="clipboard-check" class="mx-auto mb-1 h-4 w-4"></i>Quiz me</button>
                </div>
            </div>
        </div>

        <!-- Chat -->
        <div class="lg:col-span-2">
            <div class="flex h-[32rem] flex-col rounded-3xl border border-slate-100 bg-white shadow-sm">
                <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-violet-600 text-white"><i data-lucide="bot" class="h-5 w-5"></i></span>
                    <div>
                        <p class="text-sm font-bold text-slate-900">PsychTutor AI</p>
                        <p class="text-xs text-emerald-500">● Online</p>
                    </div>
                </div>

                <div class="flex-1 space-y-4 overflow-auto p-5" x-ref="log">
                    <!-- Greeting -->
                    <div class="flex gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-100 text-brand-600"><i data-lucide="bot" class="h-4 w-4"></i></span>
                        <div class="rounded-2xl rounded-tl-sm bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            Hello! 👋 I'm PsychTutor, your study buddy for the PRC Psychometrician board exam. Pick a topic or ask a question — I'll explain it clearly and simply.
                        </div>
                    </div>

                    <template x-for="(m,i) in messages" :key="i">
                        <div class="flex gap-3" :class="m.role==='user' && 'flex-row-reverse'">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg"
                                  :class="m.role==='user' ? 'bg-brand-600 text-white' : 'bg-brand-100 text-brand-600'">
                                <i :data-lucide="m.role==='user' ? 'user' : 'bot'" class="h-4 w-4"></i>
                            </span>
                            <div class="max-w-[85%]">
                                <div class="prose-ai rounded-2xl px-4 py-3 text-sm"
                                     :class="m.role==='user' ? 'rounded-tr-sm bg-brand-600 text-white' : 'rounded-tl-sm bg-slate-50 text-slate-700'"
                                     x-html="m.html"></div>
                            </div>
                        </div>
                    </template>

                    <div x-show="loading" class="flex gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-brand-100 text-brand-600"><i data-lucide="bot" class="h-4 w-4"></i></span>
                        <div class="w-full max-w-[85%] space-y-2.5 rounded-2xl rounded-tl-sm bg-slate-50 px-4 py-3.5">
                            <div class="skeleton h-3 w-3/4"></div>
                            <div class="skeleton h-3 w-full"></div>
                            <div class="skeleton h-3 w-5/6"></div>
                            <div class="skeleton h-3 w-2/3"></div>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="ask('explain')" class="flex items-center gap-2 border-t border-slate-100 p-3">
                    <input x-model="question" type="text" placeholder="Type your question… e.g. What is the difference between classical and operant conditioning?"
                           class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-100">
                    <button type="submit" :disabled="loading" class="btn-press grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30 disabled:opacity-50">
                        <i data-lucide="send" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function aiTutor(){
        return {
            topicId:'', question:'', loading:false, messages:[],
            async ask(mode){
                const q = this.question.trim();
                if (!this.topicId && !q){ this.push('bot','Please pick a topic or ask a question so I can explain it for you. 🙂'); return; }
                if (q) this.push('user', q);
                else {
                    const label = {explain:'Explain this topic',simplify:'Simplify this topic',example:'Give me examples',quiz:'Quiz me on this topic'}[mode];
                    this.push('user', label);
                }
                // Build a readable title for the downloaded PDF.
                let topicName = '';
                if (this.topicId){
                    const sel = document.querySelector(`select [value="${this.topicId}"]`) || [...document.querySelectorAll('option')].find(o=>o.value==this.topicId);
                    topicName = sel ? sel.textContent.trim() : '';
                }
                const title = topicName || q || 'Psychology Topic';
                this.loading = true; this.question='';
                this.scroll();
                try{
                    const res = await fetch('{{ route('ai.explain') }}', {
                        method:'POST',
                        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
                        body: JSON.stringify({ topic_id: this.topicId || null, question: q, mode })
                    });
                    const data = await res.json();
                    this.push('bot', data.answer || 'No response.', data.sources || [], title);
                }catch(e){ this.push('bot','There was an error with the request. Please try again. 🙏'); }
                this.loading = false;
                this.scroll();
            },
            push(role, text, sources, title){
                let html = mdToHtml(text);
                if (sources && sources.length){
                    let items = sources.map(s =>
                        `<li><a href="${s.uri}" target="_blank" rel="noopener noreferrer" class="text-brand-600 underline decoration-brand-300 hover:text-brand-700">${(s.title||s.uri).replace(/</g,'&lt;')}</a></li>`
                    ).join('');
                    html += `<div class="mt-3 border-t border-slate-200 pt-2"><p class="mb-1 flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-slate-400"><i data-lucide="link" class="h-3 w-3"></i> Sources</p><ul class="list-disc space-y-0.5 pl-5 text-xs">${items}</ul></div>`;
                }
                const downloadable = role === 'bot' && !!title;
                this.messages.push({ role, html, downloadable, title: title || '', sources: sources || [] });
                this.$nextTick(()=>{ renderIcons(); this.scroll(); });
            },
            downloadPdf(i){
                const m = this.messages[i];
                if (!m) return;
                const safe = (m.title || 'PsychReview').replace(/[\\/:*?"<>|]+/g,'').slice(0,60).trim() || 'PsychReview';
                const date = new Date().toLocaleString();
                let sourcesHtml = '';
                if (m.sources && m.sources.length){
                    const items = m.sources.map(s => `<li><a href="${s.uri}">${(s.title||s.uri).replace(/</g,'&lt;')}</a></li>`).join('');
                    sourcesHtml = `<h3 style="margin:18px 0 6px;font-size:13px;color:#475569;text-transform:uppercase;letter-spacing:.05em;">Sources (found online)</h3><ul style="font-size:11px;color:#4338ca;padding-left:18px;line-height:1.5;">${items}</ul>`;
                }
                const wrap = document.createElement('div');
                wrap.style.cssText = 'padding:32px;font-family:Inter,Arial,sans-serif;color:#0f172a;width:720px;';
                wrap.innerHTML = `
                    <div style="display:flex;align-items:center;gap:10px;border-bottom:3px solid #6366f1;padding-bottom:12px;margin-bottom:18px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;">P</div>
                        <div>
                            <div style="font-size:18px;font-weight:800;">PsychReview</div>
                            <div style="font-size:11px;color:#64748b;">PRC Psychometrician Board Exam Reviewer</div>
                        </div>
                    </div>
                    <h1 style="font-size:20px;font-weight:800;margin:0 0 4px;">${m.title.replace(/</g,'&lt;')}</h1>
                    <div style="font-size:11px;color:#94a3b8;margin-bottom:16px;">Generated by AI Tutor · ${date}</div>
                    <div class="prose-ai" style="font-size:13px;line-height:1.6;">${m.html}</div>
                    ${sourcesHtml}
                    <div style="margin-top:24px;border-top:1px solid #e2e8f0;padding-top:8px;font-size:10px;color:#94a3b8;">PsychReview · AI-generated study material. Always verify against official references.</div>
                `;
                html2pdf().set({
                    margin: 0,
                    filename: `PsychReview - ${safe}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'pt', format: 'a4', orientation: 'portrait' },
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                }).from(wrap).save();
            },
            scroll(){ this.$nextTick(()=>{ const l=this.$refs.log; if(l) l.scrollTop=l.scrollHeight; }); }
        }
    }
</script>
@endpush
