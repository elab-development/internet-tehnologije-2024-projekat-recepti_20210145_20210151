module.exports = {
  transform: {
    "^.+\\.[t|j]sx?$": "babel-jest", // koristi babel-jest za .js i .jsx fajlove
  },
  transformIgnorePatterns: [
    "/node_modules/(?!axios)/", // Jest transformiše axios
  ],
};
