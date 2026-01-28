<div class="space-y-4">
    <div class="flex justify-center">
        <img 
            src="{{ $imageUrl }}" 
            alt="Bukti Pembayaran" 
            class="max-w-full h-auto rounded-lg shadow-lg" 
            style="max-height: 400px;"
        />
    </div>
    <div class="flex justify-center">
        <a 
            href="{{ $imageUrl }}" 
            download 
            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        >
            Download Bukti Pembayaran
        </a>
    </div>
</div>