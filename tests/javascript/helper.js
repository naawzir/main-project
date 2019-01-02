const Nightmare = require('nightmare');
const Xvfb = require('xvfb');
const chai = require('chai');

const { expect } = chai.expect;

function checkText(nightmare, textToCheck, selector, done, shouldTextBePresent) {
    this.nightmare
        .evaluate(query => document.querySelector(query).innerText, selector)
        .end()
        .then((text) => {
            if (shouldTextBePresent) {
                expect(text).to.equal(textToCheck);
            } else {
                expect(text).to.not.equal(textToCheck);
            }
            this.close().then(() => {
                done();
            });
        })
        .catch((err) => {
            this.close().then(() => {
                done(err);
            });
        });
}

function setUpNightmare() {
    const nightmare = Nightmare({
        waitTimeout: 4000,
        pollInterval: 50,
    });

    nightmare.viewport(1920, 1080);

    return nightmare;
}

module.exports = {
    xvfb: function bootXvfb(options) {
        const xvfb = new Xvfb(options);

        function close() {
            return new Promise((resolve, reject) => {
                xvfb.stop(err => (err ? reject(err) : resolve()));
            });
        }

        return new Promise((resolve, reject) => {
            xvfb.start(err => (err ? reject(err) : resolve(close)));
        });
    },
    checkText,
    setUpNightmare,
    loginUser: () => {
        this.nightmare
            .insert('#username', 'testAccount')
            .insert('#password', 'test123')
            .click('form > .login-btn')
            .wait('.dashboard-main-title');
    },
    loginAccountManager: function loginAsAccountManager() {
        this.nightmare
            .goto('http://localhost/login')
            .wait('form')
            .insert('[name="login"]', 'sean@nessworthy.me')
            .insert('[name="password"]', 'rammstein')
            .click('form input[type="submit"]')
            .wait('#content-area');
    },
    goToICDashboard: () => {
        this.nightmare
            .click('.risk > a')
            .wait('.marketplace-page-current-logo')
            .click('.isg-checkbox')
            .click('.mkt-item-btn')
            .wait('.ic-app-page-title');
    },
    goToBranchesAndStaffPage: () => {
        this.nightmare
            .click('.profile-tab > a')
            .wait('.cp-title')
            .click('.tabs > :nth-child(4) > a');
    },
    cleanup: function cleanup(done, error) {
        this
            .closeBrowser()
            .then(() => {
                done(error);
            });
    },
};

