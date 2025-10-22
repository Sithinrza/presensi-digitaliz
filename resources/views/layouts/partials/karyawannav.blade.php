 <div class="fixed bottom-0 left-0 right-0 p-4 z-50">
        <div class="w-full max-w-screen-lg mx-auto bg-indigo-950 rounded-full shadow-lg">
            <div id="bottom-nav-container" class="flex items-center justify-around p-2">
                <a href="#">
                    <button type="button" class="nav-button p-2 text-indigo-950 bg-white rounded-full">
                        <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.71 2.29a1 1 0 0 0-1.42 0l-9 9a1 1 0 0 0 0 1.42A1 1 0 0 0 3 13h1v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7h1a1 1 0 0 0 1-1a1 1 0 0 0-.29-.71z"/>
                        </svg>
                    </button>
                </a>
                <a href="#">
                    <button type="button" class="nav-button p-2 text-white rounded-full">
                        <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7h10M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                        </svg>
                    </button>
                </a>
                <a href="#">
                    <button type="button" class="nav-button p-2 text-white rounded-full">
                        <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </a>
                <a href="#">
                    <button type="button" class="nav-button p-2 text-white rounded-full">
                        <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                        </svg>
                    </button>
                </a>
                <a href="#">
                    <button type="button" class="nav-button p-2 text-white rounded-full">
                        <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.949 8.949 0 0 0 12 21Zm0-13a3 3 0 1 1 0 6a3 3 0 0 1 0-6Z"/>
                        </svg>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- JavaScript untuk membuat Nav Bottom aktif -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navContainer = document.getElementById('bottom-nav-container');
            const navButtons = navContainer.querySelectorAll('.nav-button');

            navButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // 1. Hapus status aktif dari semua tombol
                    navButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'text-indigo-950');
                        btn.classList.add('text-white');
                    });

                    // 2. Tambahkan status aktif ke tombol yang diklik
                    this.classList.add('bg-white', 'text-indigo-950');
                    this.classList.remove('text-white');
                });
            });
        });
    </script>