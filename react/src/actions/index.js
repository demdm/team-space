export const signIn = (token, name) => ({
    type: 'SIGN_IN',
    token,
    name,
});

export const signOut = () => ({
    type: 'SIGN_OUT',
});

export const getUser = () => ({
    type: 'GET_USER',
});

export const changeName = name => ({
    type: 'CHANGE_NAME',
    name,
});