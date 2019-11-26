const auth = (state = [], action) => {
    switch (action.type) {
        case 'GET_USER':
            let name = localStorage.getItem('user_name');
            let token = localStorage.getItem('user_token');

            return name && token ? { name, token } : {};
        case 'LOGIN':
            localStorage.setItem('user_name', action.name);
            localStorage.setItem('user_token', action.token);

            return {
                name: action.name,
                token: action.token,
            };
        case 'LOGOUT':
            localStorage.removeItem('user_name');
            localStorage.removeItem('user_token');

            return {};
        default:
            return state;
    }
};

export default auth;