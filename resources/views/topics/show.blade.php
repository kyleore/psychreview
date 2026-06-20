@extends('layouts.app')
@section('title', $topic->title)

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12"
         x-data="aiExplain({{ $topic->id }}, @js($topic->title))">
    <a href="{{ route('topics.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Back to Topics
    </a>

    <div class="mt-5 animate-fade-up rounded-3xl border border-slate-100 bg-white p-8 shadow-xl shadow-brand-500/5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                @include('partials.icon-tile', ['category' => $topic->category, 'size' => 'md'])
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-brand-600">{{ $topic->category->name }}</p>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $topic->title }}</h1>
                </div>
            </div>
            @include('partials.difficulty', ['difficulty' => $topic->difficulty])
        </div>

        <div class="mt-7 space-y-6">
            <div class="rounded-2xl bg-slate-50 p-5">
                <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-slate-500">
                    <i data-lucide="book-marked" class="h-4 w-4 text-brand-600"></i> Definition
                </h2>
                <p class="mt-2 leading-relaxed text-slate-700">{{ $topic->definition }}</p>
            </div>

            @if($topic->key_points)
            <div class="rounded-2xl border border-slate-100 p-5">
                <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-slate-500">
                    <i data-lucide="key-round" class="h-4 w-4 text-violet-600"></i> Key Points
                </h2>
                <p class="mt-2 leading-relaxed text-slate-700">{{ $topic->key_points }}</p>
            </div>
            @endif

            @if($topic->example)
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5">
                <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-emerald-700">
                    <i data-lucide="lightbulb" class="h-4 w-4"></i> Example
                </h2>
                <p class="mt-2 leading-relaxed text-slate-700">{{ $topic->example }}</p>
            </div>
            @endif
        </div>

        <!-- AI helper -->
        <div class="mt-7 rounded-2xl border border-brand-100 bg-gradient-to-br from-brand-50 to-violet-50 p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 font-bold text-brand-800">
                    <i data-lucide="sparkles" class="h-5 w-5"></i> AI Tutor — get a clearer explanation
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button @click="explain('explain')" :disabled="loading" class="btn-press rounded-lg bg-brand-600 px-3 py-1.5 text-xs font-bold text-white shadow disabled:opacity-50">Explain</button>
                    <button @click="explain('simplify')" :disabled="loading" class="btn-press rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-brand-700 ring-1 ring-brand-200 disabled:opacity-50">Simplify</button>
                    <button @click="explain('example')" :disabled="loading" class="btn-press rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-brand-700 ring-1 ring-brand-200 disabled:opacity-50">More examples</button>
                    <button @click="explain('quiz')" :disabled="loading" class="btn-press rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-brand-700 ring-1 ring-brand-200 disabled:opacity-50">Quiz me</button>
                </div>
            </div>

            <div x-show="loading" class="mt-4 flex items-center gap-2 text-sm font-semibold text-brand-600">
                <i data-lucide="loader-circle" class="h-4 w-4 animate-spin"></i> The AI Tutor is thinking...
            </div>
            <div x-cloak x-show="answer" x-transition class="prose-ai mt-4 rounded-xl bg-white p-4 text-sm leading-relaxed text-slate-700 shadow-sm" x-html="answer"></div>
            <div x-cloak x-show="answer" class="mt-2 flex items-center justify-between">
                <p x-show="source==='offline'" class="text-xs text-slate-400">Offline explanation from your reviewer notes.</p>
                <button @click="downloadPdf()" class="btn-press ml-auto inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-brand-700 ring-1 ring-brand-200 hover:bg-brand-50">
                    <i data-lucide="file-down" class="h-3.5 w-3.5"></i> Download PDF
                </button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function aiExplain(topicId, topicTitle){
        return {
            loading:false, answer:'', source:'', sources:[], title: topicTitle || 'Psychology Topic',
            async explain(mode){
                this.loading = true; this.answer='';
                try{
                    const res = await fetch('{{ route('ai.explain') }}', {
                        method:'POST',
                        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
                        body: JSON.stringify({ topic_id: topicId, mode })
                    });
                    const data = await res.json();
                    this.sources = data.sources || [];
                    let html = mdToHtml(data.answer || 'No response.');
                    if (this.sources.length){
                        const items = this.sources.map(s => `<li><a href="${s.uri}" target="_blank" rel="noopener noreferrer" class="text-brand-600 underline">${(s.title||s.uri).replace(/</g,'&lt;')}</a></li>`).join('');
                        html += `<div class="mt-3 border-t border-slate-200 pt-2"><p class="mb-1 text-xs font-bold uppercase tracking-wide text-slate-400">Sources</p><ul class="list-disc space-y-0.5 pl-5 text-xs">${items}</ul></div>`;
                    }
                    this.answer = html;
                    this.source = data.source || '';
                }catch(e){ this.answer = 'There was an error with the AI request. Please try again.'; }
                this.loading = false;
                this.$nextTick(renderIcons);
            },
            downloadPdf(){
                if (!this.answer) return;
                const safe = (this.title || 'PsychReview').replace(/[\\/:*?"<>|]+/g,'').slice(0,60).trim() || 'PsychReview';
                const date = new Date().toLocaleString();
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
                    <h1 style="font-size:20px;font-weight:800;margin:0 0 4px;">${this.title.replace(/</g,'&lt;')}</h1>
                    <div style="font-size:11px;color:#94a3b8;margin-bottom:16px;">Generated by AI Tutor · ${date}</div>
                    <div class="prose-ai" style="font-size:13px;line-height:1.6;">${this.answer}</div>
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
            }
        }
    }
</script>
@endpush
