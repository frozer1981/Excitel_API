import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  build: {
    outDir: '../js/assets', // Папка за билднатите файлове
    emptyOutDir: true,
    rollupOptions: {
      output: {
        assetFileNames: '[name][extname]', // Изключва хешове за CSS и други ресурси
        entryFileNames: '[name].js',      // Изключва хешове за JS файлове
        chunkFileNames: '[name].js',      // Изключва хешове за chunk файлове
      },
    },
  },
});
