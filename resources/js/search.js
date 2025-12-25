import axios from 'axios';

// Debounced live-search using  Tailwind classes
document.addEventListener('DOMContentLoaded', () => {
    const DEBOUNCE_MS = 300;
    const MIN_LENGTH = 2;
    const API_PATHS = ['/api/search', '/search'];
    const MAX_RESULTS = 10;

    const input = document.querySelector('input[name="q"]');
    if (!input) return;

    // Create dropdown container with Tailwind classes
    const dropdown = document.createElement('div');
    dropdown.className = 'hidden absolute z-50 bg-white shadow-lg rounded-md w-full max-h-80 overflow-y-auto';
    // Ensure parent is positioned
    if (input.parentNode) {
        if (getComputedStyle(input.parentNode).position === 'static') {
            input.parentNode.style.position = 'relative';
        }
        input.parentNode.appendChild(dropdown);
    }


    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && e.target !== input) hideDropdown();
    });
    let timer = null;
    let lastQuery = '';

    async function fetchResults(query) {
        for (const path of API_PATHS) {
            try {
                const res = await axios.get(path, {
                    params: { q: query },
                    headers: { Accept: 'application/json' },
                    timeout: 5000,
                });
                if (Array.isArray(res.data)) return res.data;
            } catch (err) {
                // try next path
            }
        }
        return [];
    }

    function renderItems(items) {
        dropdown.innerHTML = '';
        if (!items || items.length === 0) { hideDropdown(); return; }

        const ul = document.createElement('ul');
        ul.className = 'divide-y divide-gray-100';

        items.slice(0, MAX_RESULTS).forEach(item => {
            const li = document.createElement('li');
            li.className = 'px-3 py-2 hover:bg-gray-50 cursor-pointer';
            const title = document.createElement('div');
            title.className = 'text-sm text-gray-900';
            title.textContent = item.title || item.name || 'Result';
            li.appendChild(title);

            if (item.excerpt) {
                const ex = document.createElement('div');
                ex.className = 'text-xs text-gray-500';
                ex.textContent = item.excerpt;
                li.appendChild(ex);
            }

            li.addEventListener('click', () => {
                if (item.slug) {
                    window.location.href = `/posts/${item.slug}`;
                } else if (item.url) {
                    window.location.href = item.url;
                } else {
                    const form = input.closest('form');
                    if (form) {
                        input.value = item.title || input.value;
                        form.submit();
                    }
                }
            });

            ul.appendChild(li);
        });
        dropdown.appendChild(ul);
        showDropdown();
    }
    function showDropdown() {
        dropdown.classList.remove('hidden');
        positionDropdown();
    }
    function hideDropdown() {
        dropdown.classList.add('hidden');
        dropdown.style.display = '';
    }
    function positionDropdown() {
        dropdown.style.top = (input.offsetTop + input.offsetHeight + 6) + 'px';
        dropdown.style.left = (input.offsetLeft) + 'px';
        dropdown.style.width = input.offsetWidth + 'px';
    }

    async function handleInput() {
        const q = input.value.trim();
        if (q.length < MIN_LENGTH) { lastQuery = ''; hideDropdown(); return; }
        if (q === lastQuery) return;
        lastQuery = q;
        try {
            const items = await fetchResults(q);
            renderItems(items);
        } catch (err) {
            hideDropdown();
        }
    }

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(handleInput, DEBOUNCE_MS);
    });

    // Enter fallback: let form submit if present, else go to /search
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const form = input.closest('form');

            if (!form) {
                e.preventDefault();
                window.location.href = `/search?q=${encodeURIComponent(input.value.trim())}`;
            }
        }


    });
    window.addEventListener('resize', positionDropdown);

});

