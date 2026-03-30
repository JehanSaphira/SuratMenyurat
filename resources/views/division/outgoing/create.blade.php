@extends('layouts.app')

@section('content')
<style>
    .target-dropdown {
        position: relative;
    }
    .target-trigger {
        width: 100%;
        text-align: left;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface-2);
        color: var(--text);
        padding: 10px 12px;
        cursor: pointer;
        font-weight: 500;
    }
    .target-panel {
        display: none;
        position: absolute;
        z-index: 30;
        left: 0;
        right: 0;
        margin-top: 6px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 10px;
    }
    .target-panel.open {
        display: block;
    }
    .target-toolbar {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }
    .target-toolbar input {
        flex: 1 1 240px;
        min-width: 180px;
    }
    .btn-linkish {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--primary-2);
        border-radius: 8px;
        padding: 7px 10px;
        font-weight: 600;
        cursor: pointer;
    }
    .target-count {
        font-size: 13px;
        color: var(--muted);
    }
    .target-list {
        max-height: 220px;
        overflow: auto;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #fff;
        padding: 6px;
    }
    .target-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 9px;
        border-radius: 8px;
    }
    .target-item:hover { background: #f1f5f9; }
    .target-item input[type="checkbox"] { width: auto; margin: 0; }
    .target-empty {
        color: var(--muted);
        padding: 8px;
        font-size: 14px;
    }
</style>
<div class="card">
    <h2>Buat Surat</h2>
    <form method="post" action="{{ route('division.outgoing.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Jenis Surat</label>
        <select name="letter_type_id" id="letter_type_id" required>
            @foreach($types as $type)
                <option value="{{ $type->id }}" data-fields='@json($type->effective_fields ?? $type->extra_fields ?? [])'>{{ $type->name }}</option>
            @endforeach
        </select>

        @if($parent)
            <div class="card" style="background:#f1f5f9;">
                <strong>Balasan untuk:</strong> {{ $parent->number }} - {{ $parent->subject }}
            </div>
            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
        @endif

        <label>Nomor Surat (opsional)</label>
        <input name="number" value="{{ old('number') }}" placeholder="Otomatis jika dikosongkan">
        @error('number')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <label>Perihal</label>
        <input name="subject" value="{{ old('subject') }}" required>

        <label>Isi Surat</label>
        <textarea name="body" rows="6" required>{{ old('body') }}</textarea>

        <label>Lampiran</label>
        <input type="file" name="attachment">

        @php
            $selectedTargets = collect(old('targets', []))
                ->map(fn($id) => (int) $id)
                ->whenEmpty(fn($c) => $defaultTargetId ? $c->push((int) $defaultTargetId) : $c)
                ->all();
        @endphp

        <label>Divisi Tujuan</label>
        <div class="target-dropdown" id="target-dropdown">
            <button class="target-trigger" type="button" id="target-trigger">Pilih Divisi Tujuan</button>
            <div class="target-panel" id="target-panel">
                <div class="target-toolbar">
                    <input type="search" id="target-search" placeholder="Cari nama divisi..." autocomplete="off">
                    <button class="btn-linkish" type="button" id="target-select-all">Pilih Semua</button>
                    <button class="btn-linkish" type="button" id="target-clear-all">Bersihkan</button>
                    <span class="target-count" id="target-count">0 dipilih</span>
                </div>
                <div class="target-list" id="target-list">
                    @foreach($divisions as $division)
                        <label class="target-item" data-label="{{ strtolower($division->name) }}">
                            <input
                                type="checkbox"
                                name="targets[]"
                                value="{{ $division->id }}"
                                @checked(in_array($division->id, $selectedTargets, true))
                            >
                            <span>{{ $division->name }}</span>
                        </label>
                    @endforeach
                    <div class="target-empty" id="target-empty" style="display:none;">Divisi tidak ditemukan.</div>
                </div>
            </div>
        </div>
        @error('targets')
            <div style="color:#b91c1c; margin-top:6px;">{{ $message }}</div>
        @enderror

        <div id="extra-fields" class="card" style="margin-top:12px;display:none;"></div>

        <button class="btn" type="submit">Kirim</button>
    </form>
</div>

<script>
    const select = document.getElementById('letter_type_id');
    const extraContainer = document.getElementById('extra-fields');
    const targetDropdown = document.getElementById('target-dropdown');
    const targetTrigger = document.getElementById('target-trigger');
    const targetPanel = document.getElementById('target-panel');
    const targetSearch = document.getElementById('target-search');
    const targetList = document.getElementById('target-list');
    const targetEmpty = document.getElementById('target-empty');
    const targetCount = document.getElementById('target-count');
    const targetSelectAll = document.getElementById('target-select-all');
    const targetClearAll = document.getElementById('target-clear-all');
    const targetItems = Array.from(targetList.querySelectorAll('.target-item'));

    function getCheckedTargets() {
        return Array.from(targetList.querySelectorAll('input[type="checkbox"]:checked'));
    }

    function updateTriggerText() {
        const checked = getCheckedTargets();
        if (!checked.length) {
            targetTrigger.textContent = 'Pilih Divisi Tujuan';
            return;
        }
        if (checked.length <= 2) {
            targetTrigger.textContent = checked
                .map(item => item.closest('.target-item').querySelector('span').textContent.trim())
                .join(', ');
            return;
        }
        targetTrigger.textContent = checked.length + ' divisi dipilih';
    }

    function syncTargetCount() {
        const checkedCount = getCheckedTargets().length;
        targetCount.textContent = checkedCount + ' dipilih';
        updateTriggerText();
    }

    function filterTargets() {
        const keyword = (targetSearch.value || '').trim().toLowerCase();
        let visible = 0;
        targetItems.forEach(item => {
            const show = item.dataset.label.includes(keyword);
            item.style.display = show ? 'flex' : 'none';
            if (show) visible++;
        });
        targetEmpty.style.display = visible ? 'none' : 'block';
    }

    targetList.addEventListener('change', event => {
        if (event.target.matches('input[type="checkbox"]')) {
            syncTargetCount();
        }
    });
    targetTrigger.addEventListener('click', () => {
        targetPanel.classList.toggle('open');
        if (targetPanel.classList.contains('open')) {
            targetSearch.focus();
        }
    });
    document.addEventListener('click', event => {
        if (!targetDropdown.contains(event.target)) {
            targetPanel.classList.remove('open');
        }
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            targetPanel.classList.remove('open');
        }
    });
    targetSearch.addEventListener('input', filterTargets);
    targetSelectAll.addEventListener('click', () => {
        targetItems.forEach(item => {
            if (item.style.display !== 'none') {
                item.querySelector('input[type="checkbox"]').checked = true;
            }
        });
        syncTargetCount();
    });
    targetClearAll.addEventListener('click', () => {
        targetList.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        syncTargetCount();
    });

    function renderExtra() {
        const selected = select.options[select.selectedIndex];
        const fields = JSON.parse(selected.dataset.fields || '[]');
        extraContainer.innerHTML = '';

        if (!fields.length) {
            extraContainer.style.display = 'none';
            return;
        }

        extraContainer.style.display = 'block';
        const title = document.createElement('h3');
        title.textContent = 'Field Tambahan';
        extraContainer.appendChild(title);

        fields.forEach(field => {
            const label = document.createElement('label');
            label.textContent = field.label || field.key;
            const fieldType = (field.type || 'text').toLowerCase();
            let input;
            if (fieldType === 'textarea') {
                input = document.createElement('textarea');
                input.rows = 4;
            } else {
                input = document.createElement('input');
                input.type = fieldType;
            }
            input.name = 'extra[' + field.key + ']';
            if (field.required) {
                input.required = true;
            }
            extraContainer.appendChild(label);
            extraContainer.appendChild(input);
        });
    }

    select.addEventListener('change', renderExtra);
    syncTargetCount();
    filterTargets();
    renderExtra();
</script>
@endsection
