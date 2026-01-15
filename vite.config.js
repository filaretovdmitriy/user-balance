import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
//import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
    host: '0.0.0.0',      // слушать все интерфейсы контейнера
    port: 5173,           // порт Vite
    strictPort: true,     // не искать другой порт
    hmr: {
      host: 'localhost',  // браузер подключается к localhost
      protocol: 'ws',     // WebSocket для HMR
    },
  },
});
