<nav class="w-full bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-400 dark:bg-blue-500 rounded-md"></div>
            <span class="font-bold text-lg">LightNovel</span>
        </div>

        <div class="hidden md:flex gap-6">
            <a href="#" class="hover:text-blue-500">Genre</a>
            <a href="#" class="hover:text-blue-500"></a>
            <a href="#" class="hover:text-blue-500"></a>
            <a href="#" class="hover:text-blue-500">Library</a>
        </div>
        
        <div class="flex items-center gap-4">
            <button id="darkToggle" class="text-xl">🌙</button>
            <a href="#" class="px-3 py-1 bg-blue-500 text-white rounded">Sign In</a>
        </div>

    </div>
</nav>

<script>
document.getElementById('darkToggle').onclick = () => {
    document.documentElement.classList.toggle('dark');
};
</script>
