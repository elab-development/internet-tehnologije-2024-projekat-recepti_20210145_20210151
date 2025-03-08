module.exports = {
    presets: [
      '@babel/preset-env',   // Omogućava podršku za najnoviji JavaScript
      '@babel/preset-react', // Omogućava podršku za JSX i React specifičnosti
    ],
    plugins: [
      '@babel/plugin-transform-modules-commonjs', // Konvertuje ES Modules u CommonJS
    ],
  };
  