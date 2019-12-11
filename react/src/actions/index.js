export const signIn = (id, token, name) => ({
    type: 'SIGN_IN',
    id,
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

export const setCompanyId = id => ({
    type: 'SET_ID',
    id,
});