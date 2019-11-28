import UserService from "../services/UserService";

export default (state = [], action) => {
    switch (action.type) {
        case 'SIGN_IN':
            UserService.signIn(action.token, action.name);

            return {
                name: action.name,
                token: action.token,
            };
        case 'SIGN_OUT':
            UserService.signOut();

            return {};
        default:
            return state;
    }
};