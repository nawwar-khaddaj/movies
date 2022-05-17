const { postcss } = require("tailwindcss");
let tailwindcss = require("tailwindcss");
module.exports = {
    [tailwindcss('./tailwind.config.js'), require('postcss-import'), require('autoprefixer')]
}