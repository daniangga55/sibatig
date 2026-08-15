(function () {
  'use strict';
  const $ = (selector, scope = document) => scope.querySelector(selector);
  const $$ = (selector, scope = document) => [...scope.querySelectorAll(selector)];

  const toast = $('#toast');
  let toastTimer;
  function showToast(message) {
    if (!toast) return;
    $('p', toast).textContent = message;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 3000);
  }

  $$('[data-toast]').forEach(button => button.addEventListener('click', () => showToast(button.dataset.toast)));

  const sidebar = $('#sidebar');
  const menuButton = $('#menuButton');
  const overlay = $('#sidebarOverlay');
  function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('open');
    menuButton?.setAttribute('aria-expanded', 'false');
    if (overlay) overlay.hidden = true;
  }
  menuButton?.addEventListener('click', () => {
    const open = sidebar.classList.toggle('open');
    menuButton.setAttribute('aria-expanded', String(open));
    overlay.hidden = !open;
  });
  overlay?.addEventListener('click', closeSidebar);

  const sectionTitles = { dashboard: 'Dedi Haryadi', pkpt: 'PKPT 2026', spt: 'Rekap SPT', monitoring: 'Monitoring & Evaluasi', calendar: 'Kalender Kegiatan', documents: 'Dokumen', team: 'Tim Irban Tiga', announcements: 'Pengumuman' };
  function openSection(id, updateHash = true) {
    const section = $(`[data-section="${id}"]`);
    if (!section) return;
    $$('.view-section').forEach(item => item.classList.toggle('active', item === section));
    $$('.nav-item[data-section-link]').forEach(item => item.classList.toggle('active', item.dataset.sectionLink === id));
    const name = $('.page-heading strong');
    if (name) name.textContent = sectionTitles[id] || 'SIBATIG';
    if (updateHash) history.replaceState(null, '', `#${id}`);
    const previousScrollBehavior = document.documentElement.style.scrollBehavior;
    document.documentElement.style.scrollBehavior = 'auto';
    window.scrollTo(0, 0);
    document.documentElement.style.scrollBehavior = previousScrollBehavior;
    closeSidebar();
  }
  $$('[data-section-link]').forEach(link => link.addEventListener('click', event => {
    const id = link.dataset.sectionLink;
    if ($(`[data-section="${id}"]`)) { event.preventDefault(); openSection(id); }
  }));
  if (location.hash) openSection(location.hash.slice(1), false);

  function setupPopover(buttonId, popoverId) {
    const button = $(buttonId), popover = $(popoverId);
    if (!button || !popover) return;
    button.addEventListener('click', event => {
      event.stopPropagation();
      $$('.popover').filter(item => item !== popover).forEach(item => item.hidden = true);
      popover.hidden = !popover.hidden;
      button.setAttribute('aria-expanded', String(!popover.hidden));
    });
    document.addEventListener('click', event => {
      if (!popover.contains(event.target) && event.target !== button) {
        popover.hidden = true;
        button.setAttribute('aria-expanded', 'false');
      }
    });
  }
  setupPopover('#notificationButton', '#notificationsPopover');
  setupPopover('#profileButton', '#profilePopover');

  const filterButtons = $$('.filter-button');
  const rows = $$('#activityRows tr');
  const search = $('#globalSearch');
  let activeFilter = 'all';
  function filterRows() {
    const query = (search?.value || '').trim().toLowerCase();
    let count = 0;
    rows.forEach(row => {
      const matchesFilter = activeFilter === 'all' || row.dataset.status === activeFilter;
      const matchesSearch = !query || row.textContent.toLowerCase().includes(query);
      row.hidden = !(matchesFilter && matchesSearch);
      if (!row.hidden) count++;
    });
    const visibleCount = $('#visibleCount'); if (visibleCount) visibleCount.textContent = String(count);
    const emptyState = $('#emptyState'); if (emptyState) emptyState.hidden = count > 0;
  }
  filterButtons.forEach(button => button.addEventListener('click', () => {
    activeFilter = button.dataset.filter;
    filterButtons.forEach(item => item.classList.toggle('active', item === button));
    filterRows();
  }));
  search?.addEventListener('input', () => { openSection('dashboard'); filterRows(); });
  document.addEventListener('keydown', event => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k' && search) {
      event.preventDefault(); search.focus();
    }
    if (event.key === 'Escape') closeSidebar();
  });

  const escapeHtml = value => String(value ?? '').replace(/[&<>'"]/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' })[character]);
  const sptData = Array.isArray(window.SPT_DATA) ? window.SPT_DATA : [];
  const sptByPkpt = new Map();
  sptData.filter(item => item.relation === 'PKPT' && item.pkptNo).forEach(item => {
    if (!sptByPkpt.has(item.pkptNo)) sptByPkpt.set(item.pkptNo, []);
    sptByPkpt.get(item.pkptNo).push(item);
  });

  const sptTableBody = $('#sptRows');
  if (sptTableBody) {
    sptTableBody.innerHTML = sptData.map(item => {
      const relationKey = item.relation === 'PKPT' ? 'pkpt' : 'non-pkpt';
      const statusKey = item.status === 'ON PROGRES' ? 'progress' : 'complete';
      const matchLabel = item.match === 'contextual' ? '<small class="match-label contextual">≈ Kontekstual</small>' : item.match === 'thematic' ? '<small class="match-label thematic">Subkegiatan</small>' : '';
      const warning = item.dateWarning ? '<span class="date-warning" title="Tahun berbeda dari tanggal SPT pada file sumber">!</span>' : '';
      const lhp = item.lhp ? `<strong>${escapeHtml(item.lhp)}</strong><small>${escapeHtml(item.lhpDate || '')}</small>` : '<span class="muted-value">Belum tercatat</span>';
      const relation = item.relation === 'PKPT' ? `<span class="relation-badge pkpt">PKPT No. ${item.pkptNo}</span>${matchLabel}` : '<span class="relation-badge non-pkpt">NON PKPT</span>';
      return `<tr data-relation="${relationKey}" data-status="${statusKey}">
        <td><span class="pkpt-number">${item.no}</span></td>
        <td><div class="spt-number"><strong>${escapeHtml(item.number)}</strong><small>${escapeHtml(item.date)}</small></div></td>
        <td><div class="spt-subject"><strong>${escapeHtml(item.subject)}</strong><small>${escapeHtml(item.obrik || '—')}</small></div></td>
        <td><div class="spt-dates"><strong>${escapeHtml(item.start)} – ${escapeHtml(item.end)} ${warning}</strong><small>Laporan: ${escapeHtml(item.report)}</small></div></td>
        <td><div class="spt-lhp">${lhp}</div></td>
        <td><span class="type-badge ${item.type.toLowerCase()}">${escapeHtml(item.type)}</span></td>
        <td><div class="relation-wrap">${relation}</div></td>
        <td><span class="integration-status ${statusKey}"><i></i>${item.status === 'ON PROGRES' ? 'On progress' : 'Selesai'}</span></td>
      </tr>`;
    }).join('');
  }

  const pkptRows = $$('#pkptRows tr');
  pkptRows.forEach(row => {
    const number = Number($('.pkpt-number', row)?.textContent);
    const matches = sptByPkpt.get(number) || [];
    const hasSpt = matches.length > 0;
    const hasProgress = matches.some(item => item.status === 'ON PROGRES');
    const needsReview = matches.some(item => item.match === 'contextual');
    row.dataset.spt = hasSpt ? 'yes' : 'no';
    const realizationCell = document.createElement('td');
    const statusCell = document.createElement('td');
    if (hasSpt) {
      const last = matches[matches.length - 1];
      realizationCell.innerHTML = `<button class="spt-count-link" type="button" data-open-pkpt-spt="${number}"><strong>${matches.length} SPT</strong><small>Terakhir ${escapeHtml(last.date)}</small></button>${needsReview ? '<span class="match-note" title="Pemetaan berdasarkan konteks Pengadaan Barang/Jasa">≈ Verifikasi</span>' : ''}`;
      statusCell.innerHTML = `<span class="integration-status ${hasProgress ? 'progress' : 'complete'}"><i></i>${hasProgress ? 'On progress' : 'Sudah ada SPT'}</span>`;
    } else {
      realizationCell.innerHTML = '<span class="no-spt">Belum terbit</span>';
      statusCell.innerHTML = '<span class="integration-status pending"><i></i>Belum ada SPT</span>';
    }
    row.children[2].after(realizationCell, statusCell);
  });

  const pkptSearch = $('#pkptSearch');
  const pkptFilters = $$('.pkpt-filter');
  const pkptSptFilters = $$('.pkpt-spt-filter');
  let activePkptFilter = 'all';
  let activePkptSptFilter = 'all';
  function filterPkptRows() {
    const query = (pkptSearch?.value || '').trim().toLocaleLowerCase('id');
    let count = 0;
    pkptRows.forEach(row => {
      const matchesCategory = activePkptFilter === 'all' || row.dataset.category === activePkptFilter;
      const matchesSpt = activePkptSptFilter === 'all' || row.dataset.spt === activePkptSptFilter;
      const matchesQuery = !query || row.textContent.toLocaleLowerCase('id').includes(query);
      row.hidden = !(matchesCategory && matchesSpt && matchesQuery);
      if (!row.hidden) count++;
    });
    const visibleCount = $('#pkptVisibleCount');
    if (visibleCount) visibleCount.textContent = String(count);
    const empty = $('#pkptEmpty');
    if (empty) empty.hidden = count > 0;
  }
  pkptFilters.forEach(button => button.addEventListener('click', () => {
    activePkptFilter = button.dataset.pkptFilter;
    pkptFilters.forEach(item => {
      const active = item === button;
      item.classList.toggle('active', active);
      item.setAttribute('aria-pressed', String(active));
    });
    filterPkptRows();
  }));
  pkptSptFilters.forEach(button => button.addEventListener('click', () => {
    activePkptSptFilter = button.dataset.pkptSpt;
    pkptSptFilters.forEach(item => item.classList.toggle('active', item === button));
    filterPkptRows();
  }));
  pkptSearch?.addEventListener('input', filterPkptRows);

  const sptRows = $$('#sptRows tr');
  const sptSearch = $('#sptSearch');
  const sptFilters = $$('.spt-filter');
  let activeSptFilter = 'all';
  function filterSptRows() {
    const query = (sptSearch?.value || '').trim().toLocaleLowerCase('id');
    let count = 0;
    sptRows.forEach(row => {
      const matchesFilter = activeSptFilter === 'all' || row.dataset.relation === activeSptFilter || (activeSptFilter === 'progress' && row.dataset.status === 'progress');
      const matchesQuery = !query || row.textContent.toLocaleLowerCase('id').includes(query);
      row.hidden = !(matchesFilter && matchesQuery);
      if (!row.hidden) count++;
    });
    if ($('#sptVisibleCount')) $('#sptVisibleCount').textContent = String(count);
    if ($('#sptEmpty')) $('#sptEmpty').hidden = count > 0;
  }
  sptFilters.forEach(button => button.addEventListener('click', () => {
    activeSptFilter = button.dataset.sptFilter;
    sptFilters.forEach(item => item.classList.toggle('active', item === button));
    filterSptRows();
  }));
  sptSearch?.addEventListener('input', filterSptRows);

  $$('[data-open-pkpt-spt]').forEach(button => button.addEventListener('click', () => {
    const pkptNumber = button.dataset.openPkptSpt;
    activeSptFilter = 'all';
    sptFilters.forEach(item => item.classList.toggle('active', item.dataset.sptFilter === 'all'));
    if (sptSearch) sptSearch.value = `PKPT No. ${pkptNumber}`;
    filterSptRows();
    openSection('spt');
  }));

  function downloadTableCsv(headers, tableRows, filename) {
    const quote = value => `"${value.replace(/\s+/g, ' ').trim().replace(/"/g, '""')}"`;
    const csv = [headers, ...tableRows.map(row => $$('td', row).map(cell => cell.textContent))].map(row => row.map(quote).join(';')).join('\r\n');
    const blob = new Blob([`\ufeff${csv}`], { type: 'text/csv;charset=utf-8' });
    const objectUrl = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = objectUrl; link.download = filename; document.body.appendChild(link); link.click(); link.remove(); URL.revokeObjectURL(objectUrl);
  }
  $('#exportPkpt')?.addEventListener('click', () => {
    const visibleRows = pkptRows.filter(row => !row.hidden);
    downloadTableCsv($$('.pkpt-table thead th').map(cell => cell.textContent.trim()), visibleRows, 'PKPT-Irban-3-2026-terintegrasi-SPT.csv');
    showToast(`${visibleRows.length} data PKPT berhasil diekspor`);
  });
  $('#exportSpt')?.addEventListener('click', () => {
    const visibleRows = sptRows.filter(row => !row.hidden);
    downloadTableCsv($$('.spt-table thead th').map(cell => cell.textContent.trim()), visibleRows, 'Rekap-SPT-Irban-3-2026.csv');
    showToast(`${visibleRows.length} data SPT berhasil diekspor`);
  });

  const calendarGrid = $('#calendarGrid');
  if (calendarGrid) {
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const shortMonthMap = { Jan: 0, Feb: 1, Mar: 2, Apr: 3, Mei: 4, Jun: 5, Jul: 6, Agu: 7, Sep: 8, Okt: 9, Nov: 10, Des: 11 };
    const dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    const parseIndonesianDate = value => {
      const [day, month, year] = String(value).split(' ');
      return new Date(Number(year), shortMonthMap[month], Number(day));
    };
    const calendarEvents = sptData.map(item => ({ ...item, startDate: parseIndonesianDate(item.start), endDate: parseIndonesianDate(item.end) }));
    const monthSelect = $('#calendarMonth');
    const previousButton = $('#calendarPrev');
    const nextButton = $('#calendarNext');
    const eventList = $('#calendarEventList');
    let visibleMonth = Number(monthSelect?.value ?? 7);
    let selectedCalendarDate = null;
    const sameDay = (first, second) => first && second && first.getFullYear() === second.getFullYear() && first.getMonth() === second.getMonth() && first.getDate() === second.getDate();
    const isActiveOn = (event, date) => event.startDate <= date && event.endDate >= date;
    const formatShortDate = date => `${date.getDate()} ${monthNames[date.getMonth()].slice(0, 3)} 2026`;

    function renderCalendarAgenda(events, label) {
      $('#calendarAgendaTitle').textContent = label;
      $('#calendarEventCount').textContent = `${events.length} SPT`;
      if (!events.length) {
        eventList.innerHTML = '<div class="calendar-empty"><span>○</span><strong>Tidak ada SPT</strong><p>Tidak terdapat rencana pemeriksaan pada periode ini.</p></div>';
        return;
      }
      eventList.innerHTML = events.sort((a, b) => a.startDate - b.startDate).map(item => `<article class="calendar-spt-item">
        <div class="calendar-spt-date"><strong>${item.startDate.getDate()}</strong><span>${monthNames[item.startDate.getMonth()].slice(0, 3).toUpperCase()}</span></div>
        <div class="calendar-spt-info"><div><span class="type-badge ${item.type.toLowerCase()}">${escapeHtml(item.type)}</span><span class="relation-badge ${item.relation === 'PKPT' ? 'pkpt' : 'non-pkpt'}">${escapeHtml(item.relation)}</span></div><strong>${escapeHtml(item.subject)}</strong><small>${formatShortDate(item.startDate)} – ${formatShortDate(item.endDate)} · ${escapeHtml(item.obrik || '—')}</small></div>
        <button class="calendar-open-spt" type="button" data-calendar-spt="${escapeHtml(item.number)}" aria-label="Lihat SPT ${escapeHtml(item.number)}">→</button>
      </article>`).join('');
    }

    function renderCalendar() {
      const firstDay = new Date(2026, visibleMonth, 1);
      const lastDay = new Date(2026, visibleMonth + 1, 0);
      const mondayOffset = (firstDay.getDay() + 6) % 7;
      const gridStart = new Date(2026, visibleMonth, 1 - mondayOffset);
      const today = new Date();
      const monthEvents = calendarEvents.filter(event => event.startDate <= lastDay && event.endDate >= firstDay);
      $('#calendarTitle').textContent = `${monthNames[visibleMonth]} 2026`;
      monthSelect.value = String(visibleMonth);
      previousButton.disabled = visibleMonth === 0;
      nextButton.disabled = visibleMonth === 11;
      let markup = dayNames.map(day => `<b>${day}</b>`).join('');
      for (let index = 0; index < 42; index++) {
        const date = new Date(gridStart); date.setDate(gridStart.getDate() + index);
        const activeEvents = calendarEvents.filter(event => isActiveOn(event, date));
        const inMonth = date.getMonth() === visibleMonth;
        const isToday = today.getFullYear() === 2026 && sameDay(date, today);
        const selected = sameDay(date, selectedCalendarDate);
        const isoDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        const marks = activeEvents.slice(0, 4).map(event => `<i class="${event.type.toLowerCase()}" title="${escapeHtml(event.number)}"></i>`).join('');
        markup += `<button type="button" class="calendar-day${inMonth ? '' : ' outside-month'}${isToday ? ' today' : ''}${selected ? ' selected' : ''}${activeEvents.length ? ' has-events' : ''}" data-calendar-date="${isoDate}" aria-label="${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()}, ${activeEvents.length} SPT"${inMonth ? '' : ' disabled'}><span class="calendar-day-number">${date.getDate()}</span><div class="calendar-event-marks">${marks}</div>${activeEvents.length ? `<small>${activeEvents.length} SPT</small>` : ''}</button>`;
      }
      calendarGrid.innerHTML = markup;
      if (selectedCalendarDate) {
        const selectedEvents = calendarEvents.filter(event => isActiveOn(event, selectedCalendarDate));
        renderCalendarAgenda(selectedEvents, `SPT pada ${selectedCalendarDate.getDate()} ${monthNames[selectedCalendarDate.getMonth()]} 2026`);
      } else {
        renderCalendarAgenda(monthEvents, `SPT ${monthNames[visibleMonth]} 2026`);
      }
    }

    calendarGrid.addEventListener('click', event => {
      const day = event.target.closest('[data-calendar-date]');
      if (!day) return;
      const [year, month, date] = day.dataset.calendarDate.split('-').map(Number);
      selectedCalendarDate = new Date(year, month - 1, date);
      if (selectedCalendarDate.getMonth() !== visibleMonth) visibleMonth = selectedCalendarDate.getMonth();
      renderCalendar();
    });
    previousButton.addEventListener('click', () => { if (visibleMonth > 0) { visibleMonth--; selectedCalendarDate = null; renderCalendar(); } });
    nextButton.addEventListener('click', () => { if (visibleMonth < 11) { visibleMonth++; selectedCalendarDate = null; renderCalendar(); } });
    monthSelect.addEventListener('change', () => { visibleMonth = Number(monthSelect.value); selectedCalendarDate = null; renderCalendar(); });
    eventList.addEventListener('click', event => {
      const button = event.target.closest('[data-calendar-spt]');
      if (!button) return;
      activeSptFilter = 'all';
      sptFilters.forEach(item => item.classList.toggle('active', item.dataset.sptFilter === 'all'));
      if (sptSearch) sptSearch.value = button.dataset.calendarSpt;
      filterSptRows(); openSection('spt');
    });
    renderCalendar();
  }

  const modal = $('#activityModal');
  $$('[data-modal-open="activityModal"]').forEach(button => button.addEventListener('click', () => modal?.showModal()));
  $('#activityForm')?.addEventListener('submit', event => {
    const submitter = event.submitter;
    if (submitter?.value === 'cancel') return;
    event.preventDefault();
    const required = $$('[required]', event.currentTarget);
    if (required.some(input => !input.value)) { required.find(input => !input.value)?.focus(); return; }
    modal.close(); event.currentTarget.reset(); showToast('Kegiatan baru berhasil ditambahkan');
  });

  $$('[data-toggle-password]').forEach(button => button.addEventListener('click', () => {
    const input = document.getElementById(button.dataset.togglePassword);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    button.setAttribute('aria-label', input.type === 'password' ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
  }));

  const loginForm = $('#loginForm');
  loginForm?.addEventListener('submit', event => {
    event.preventDefault();
    const fields = $$('input[required]', loginForm);
    fields.forEach(input => input.closest('.form-field').classList.toggle('invalid', !input.checkValidity()));
    if (!loginForm.checkValidity()) { fields.find(input => !input.checkValidity())?.focus(); return; }
    const submit = $('button[type="submit"]', loginForm);
    submit.disabled = true; submit.querySelector('span').textContent = 'Memverifikasi...';
    setTimeout(() => { location.href = 'index.html'; }, 650);
  });
  $$('input', loginForm || document.createElement('form')).forEach(input => input.addEventListener('input', () => input.closest('.form-field')?.classList.remove('invalid')));

  const forgotForm = $('#forgotForm');
  forgotForm?.addEventListener('submit', event => {
    event.preventDefault();
    const email = $('#resetEmail');
    email.closest('.form-field').classList.toggle('invalid', !email.checkValidity());
    if (!email.checkValidity()) { email.focus(); return; }
    $('#sentEmail').textContent = email.value;
    $('#resetFormWrap').hidden = true; $('#resetSuccess').hidden = false;
  });
  $('#resetEmail')?.addEventListener('input', event => event.target.closest('.form-field').classList.remove('invalid'));
  $('#resendButton')?.addEventListener('click', () => showToast('Tautan pemulihan berhasil dikirim ulang'));
})();
