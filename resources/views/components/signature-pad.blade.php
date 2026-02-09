<div
    x-data="{
        drawing: false,
        ctx: null,
        init() {
            const canvas = this.$refs.canvas
            this.ctx = canvas.getContext('2d')
            this.ctx.lineWidth = 2
            this.ctx.lineCap = 'round'
            this.ctx.strokeStyle = '#000'
        },
        start(e) {
            this.drawing = true
            this.ctx.beginPath()
            this.ctx.moveTo(e.offsetX, e.offsetY)
        },
        draw(e) {
            if (!this.drawing) return
            this.ctx.lineTo(e.offsetX, e.offsetY)
            this.ctx.stroke()

            $wire.set(
                @js($getStatePath()),
                this.$refs.canvas.toDataURL('image/png')
            )
        },
        stop() {
            this.drawing = false
        },
        clear() {
            this.ctx.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height)
            $wire.set(@js($getStatePath()), null)
        }
    }"
    class="flex flex-col items-center space-y-3"
>

    <canvas
        x-ref="canvas"
        width="300"
        height="300"
        class="border-2 border-gray-800 rounded-lg bg-white"
        style="touch-action:none"
        @mousedown="start"
        @mousemove="draw"
        @mouseup="stop"
        @mouseleave="stop"
    ></canvas>

    <button
        type="button"
        class="text-sm text-red-600 hover:underline"
        @click="clear"
    >
        Hapus Tanda Tangan
    </button>
</div>
