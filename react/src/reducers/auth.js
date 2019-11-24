const auth = (state = [], action) => {
    switch (action.type) {
        case 'LOGIN':
            return [
                ...state,
                {
                    name: action.name,
                    token: action.token,
                }
            ];
        case 'LOGOUT':
            return [];
        default:
            return state;
    }
};

export default auth;