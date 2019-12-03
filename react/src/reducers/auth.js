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
        case 'GET_USER':
            return state;
        case 'CHANGE_NAME':
            UserService.name = action.name;

            state.name = action.name;

            return state;
        default:
            return state;
    }
};