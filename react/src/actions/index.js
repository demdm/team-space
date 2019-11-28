export const signIn = (token, name) => ({
    type: 'SIGN_IN',
    token,
    name,
});

export const signOut = () => ({
    type: 'SIGN_OUT',
});