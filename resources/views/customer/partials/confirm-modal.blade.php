{{-- Custom verification popup (replaces native alert/confirm).
     Open it via: ShopLink.confirm({ title, message, confirmText, cancelText, onConfirm }) --}}
<div id="confirmModal" class="fixed inset-0 z-[11000] hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm" role="alertdialog" aria-modal="true" aria-labelledby="confirmModalTitle">
    <div class="w-full max-w-sm rounded-3xl bg-white p-6 text-center shadow-2xl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-black text-xl text-white">
            <i class="fas fa-exclamation"></i>
        </div>
        <h3 id="confirmModalTitle" class="text-lg font-bold tracking-tight text-black">Are you sure?</h3>
        <p id="confirmModalMessage" class="mt-1 text-sm text-gray-500">This action cannot be undone.</p>
        <div class="mt-6 flex flex-col gap-2 sm:flex-row">
            <button id="confirmModalCancel" type="button" class="min-h-11 flex-1 rounded-full border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-gray-100 active:scale-95">
                Cancel
            </button>
            <button id="confirmModalOk" type="button" class="min-h-11 flex-1 rounded-full bg-black px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 active:scale-95">
                Confirm
            </button>
        </div>
    </div>
</div>
