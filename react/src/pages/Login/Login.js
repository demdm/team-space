import React from 'react';
import {Col, Container, Row} from "react-bootstrap";
import Card from "react-bootstrap/Card";
import * as Yup from 'yup';
import CommonForm from './../../components/common/Form';
import { login } from './../../actions';
import { connect } from "react-redux";

let Login = props => {

    const Schema = Yup.object().shape({
        email: Yup.string()
            .email('Invalid email')
            .required('Required'),
        password: Yup.string()
            .min(6, 'Too Short!')
            .max(255, 'Too Long!')
            .required('Required'),
    });

    const Fields = [
        {
            name: 'email',
            type: 'email',
            label: 'Email',
        },
        {
            name: 'password',
            type: 'password',
            label: 'Password',
        },
    ];

    return (
        <>
            <Container>
                <Row>
                    <Col md={{ span: 6, offset: 3 }} lg={{ span: 4, offset: 4 }}>
                        <br/>
                        <h1 align={'center'}>Login</h1>
                        <br/>
                        <Card style={{ width: '100%' }}>
                            <Card.Body>
                                <CommonForm
                                    schema={Schema}
                                    fields={Fields}
                                    url='/auth/login'
                                    submit_text='Login'
                                    on_success_cb={props.onSuccessCallBack}
                                    success_message={props.name + ', you logged in successful!'}
                                />
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
};

const mapStateToProps = state => ({
    name: (state.auth.length ? state.auth[state.auth.length - 1]["name"] : null),
});

const mapDispatchToProps = dispatch => ({
    onSuccessCallBack: data => dispatch(login(data.token, data.name)),
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(Login);
