import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    globals: true, // Omogućava globalne funkcije kao što su beforeEach, afterEach
    environment: 'jsdom', 
    setupFiles: ['./vitest.setup.js'], 
    coverage: {
      reporter: ['text', 'json', 'html'], 
    },
    transform: {
      '^.+\\.jsx?$': 'babel-jest', 
    },
  },
});
