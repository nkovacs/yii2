if (typeof initMochaPhantomJS === 'function') {
    initMochaPhantomJS();
}
// this needs to be executed before mocha-sinon
mocha.setup('bdd');
