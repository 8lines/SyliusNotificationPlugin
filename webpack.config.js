const Encore = require('@symfony/webpack-encore');
const path = require('path');

Encore.setOutputPath(`public/build/`)
    .setPublicPath(`/public/build/`)
    .addEntry(`8lines-sylius-notification-plugin-admin`, path.resolve(__dirname, `./assets/admin/entry.js`))
    .cleanupOutputBeforeBuild()
    .disableSingleRuntimeChunk()
    .enableSassLoader();

const distConfig = Encore.getWebpackConfig();
distConfig.name = `8lines-plugin-dist`;

Encore.reset();

Encore.setOutputPath(`public/build/8lines/sylius-notification-plugin/admin/`)
  .setPublicPath(`/build/8lines/sylius-notification-plugin/admin/`)
  .addEntry(`8lines-sylius-notification-plugin-admin`, path.resolve(__dirname, `assets/admin/entry.js`))
  .disableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableSassLoader();

const adminConfig = Encore.getWebpackConfig();
adminConfig.name = `8lines-sylius-notification-plugin-admin`;

module.exports = [adminConfig, distConfig];
