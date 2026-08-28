<div
    class="sibatig-topbar-clock sibatig-realtime-clock"
    x-data="{
        now: new Date(),
        timer: null,
        init() {
            this.timer = window.setInterval(() => this.now = new Date(), 1000)
        },
        destroy() {
            if (this.timer) window.clearInterval(this.timer)
        },
        timeLabel() {
            return new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            }).format(this.now).replaceAll('.', ':')
        },
        dateLabel() {
            return new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                weekday: 'short',
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            }).format(this.now)
        },
    }"
>
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm1 5v4.6l3.2 1.9-1 1.7-4.2-2.6V7h2Z"/></svg>
    <time
        :datetime="now.toISOString()"
        :aria-label="`${dateLabel()}, pukul ${timeLabel()} Waktu Indonesia Barat`"
    >
        <strong>
            <span x-text="timeLabel().slice(0, 5)">{{ now('Asia/Jakarta')->format('H:i') }}</span><span class="sibatig-clock-seconds" x-text="timeLabel().slice(5)">:{{ now('Asia/Jakarta')->format('s') }}</span>
        </strong>
        <small><span x-text="dateLabel()">{{ now('Asia/Jakarta')->locale('id')->translatedFormat('D, d M Y') }}</span> &middot; WIB</small>
    </time>
    <i aria-hidden="true"></i>
</div>
