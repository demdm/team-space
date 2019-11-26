export const login = (token, name) => ({
    type: 'LOGIN',
    token,
    name,
});

export const logout = () => ({
    type: 'LOGOUT',
});

export const getUser = () => ({
    type: 'GET_USER',
});
