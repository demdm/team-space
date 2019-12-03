export default {

    /** @var {string} */
    tokenKeyName: 'token',

    /** @var {string} */
    nameKeyName: 'user',

    /**
     * @returns {boolean}
     */
    isSignedIn: function () {
        return localStorage.getItem(this.tokenKeyName) !== null
            && localStorage.getItem(this.nameKeyName) !== null;
    },

    /**
     * @returns {string}|{null}
     */
    get token() {
        return  localStorage.getItem(this.tokenKeyName);
    },

    /**
     * @returns {string}|{null}
     */
    get name() {
        return localStorage.getItem(this.nameKeyName);
    },

    /**
     * @param {string} name
     * @returns {void}
     */
    set name(name) {
        localStorage.setItem(this.nameKeyName, name);
    },

    /**
     * @param {string} token
     * @param {string} name
     */
    signIn: function (token, name) {
        localStorage.setItem(this.tokenKeyName, token);
        localStorage.setItem(this.nameKeyName, name);
    },

    /**
     * @return {void}
     */
    signOut: function () {
        localStorage.removeItem(this.tokenKeyName);
        localStorage.removeItem(this.nameKeyName);
    },

};
