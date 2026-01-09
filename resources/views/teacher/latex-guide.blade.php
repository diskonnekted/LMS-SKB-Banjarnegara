<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contoh LaTeX untuk Soal</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="prose max-w-none">
                        <h1>Pedoman Singkat</h1>
                        <ul>
                            <li>Rumus inline gunakan <code>$...$</code>, contoh: <code>$a^2 + b^2 = c^2$</code>.</li>
                            <li>Rumus display gunakan <code>$$...$$</code> atau <code>\[...\]</code>.</li>
                            <li>Gunakan <code>\\</code> untuk pindah baris pada lingkungan tertentu.</li>
                        </ul>

                        <h2>Contoh Cepat</h2>

                        <div class="not-prose grid grid-cols-1 gap-4">
                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="text-xs font-semibold text-gray-600">Inline</div>
                                <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Tulis</div>
                                        <pre class="m-0 overflow-x-auto text-sm"><code>$a^2 + b^2 = c^2$</code></pre>
                                    </div>
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Hasil</div>
                                        <div class="latex-render">$a^2 + b^2 = c^2$</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="text-xs font-semibold text-gray-600">Pecahan & Akar</div>
                                <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Tulis</div>
                                        <pre class="m-0 overflow-x-auto text-sm"><code>$$\frac{2x+1}{x-3} = 5$$

$$\sqrt{a^2+b^2}$$</code></pre>
                                    </div>
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Hasil</div>
                                        <div class="latex-render">$$\frac{2x+1}{x-3} = 5$$

$$\sqrt{a^2+b^2}$$</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="text-xs font-semibold text-gray-600">Pangkat, Indeks, dan Huruf Yunani</div>
                                <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Tulis</div>
                                        <pre class="m-0 overflow-x-auto text-sm"><code>$x_1, x_2, \dots, x_n$

$e^{i\pi} + 1 = 0$

$\alpha + \beta = \gamma$</code></pre>
                                    </div>
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Hasil</div>
                                        <div class="latex-render">$x_1, x_2, \dots, x_n$

$e^{i\pi} + 1 = 0$

$\alpha + \beta = \gamma$</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="text-xs font-semibold text-gray-600">Limit, Sigma, Integral</div>
                                <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Tulis</div>
                                        <pre class="m-0 overflow-x-auto text-sm"><code>$$\lim_{x \to 0} \frac{\sin x}{x} = 1$$

$$\sum_{k=1}^{n} k = \frac{n(n+1)}{2}$$

$$\int_0^1 x^2\,dx = \frac{1}{3}$$</code></pre>
                                    </div>
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Hasil</div>
                                        <div class="latex-render">$$\lim_{x \to 0} \frac{\sin x}{x} = 1$$

$$\sum_{k=1}^{n} k = \frac{n(n+1)}{2}$$

$$\int_0^1 x^2\,dx = \frac{1}{3}$$</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="text-xs font-semibold text-gray-600">Matriks</div>
                                <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Tulis</div>
                                        <pre class="m-0 overflow-x-auto text-sm"><code>$$\begin{bmatrix}
1 & 2 \\
3 & 4
\end{bmatrix}$$</code></pre>
                                    </div>
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Hasil</div>
                                        <div class="latex-render">$$\begin{bmatrix}
1 & 2 \\
3 & 4
\end{bmatrix}$$</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-lg border bg-gray-50 p-4">
                                <div class="text-xs font-semibold text-gray-600">Sistem Persamaan</div>
                                <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Tulis</div>
                                        <pre class="m-0 overflow-x-auto text-sm"><code>$$\begin{cases}
2x + y = 7 \\
x - y = 1
\end{cases}$$</code></pre>
                                    </div>
                                    <div class="rounded border bg-white p-3">
                                        <div class="text-xs text-gray-500 mb-1">Hasil</div>
                                        <div class="latex-render">$$\begin{cases}
2x + y = 7 \\
x - y = 1
\end{cases}$$</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Simbol Umum</h2>
                        <ul>
                            <li>Pangkat: <code>x^2</code>, indeks: <code>x_2</code>, gabungan: <code>x_2^3</code></li>
                            <li>Pecahan: <code>\frac{a}{b}</code></li>
                            <li>Akar: <code>\sqrt{x}</code>, akar pangkat n: <code>\sqrt[n]{x}</code></li>
                            <li>Panah: <code>\to</code>, <code>\Rightarrow</code></li>
                            <li>Perkalian titik: <code>\cdot</code>, kali silang: <code>\times</code></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <script>
        (function () {
            const nodes = document.querySelectorAll('.latex-render');
            nodes.forEach((el) => {
                try {
                    renderMathInElement(el, {
                        delimiters: [
                            { left: "$$", right: "$$", display: true },
                            { left: "\\[", right: "\\]", display: true },
                            { left: "$", right: "$", display: false },
                            { left: "\\(", right: "\\)", display: false },
                        ],
                        throwOnError: false,
                    });
                } catch (e) {
                }
            });
        })();
    </script>
</x-app-layout>
