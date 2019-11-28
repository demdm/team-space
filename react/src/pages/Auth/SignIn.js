import React from 'react';
import {Col, Container, Row} from "react-bootstrap";
import Card from "react-bootstrap/Card";
import * as Yup from 'yup';
import CommonForm from './../../components/common/Form';
import {signIn} from './../../actions';
import { useDispatch, useSelector } from "react-redux";

export default () => {
    const dispatch = useDispatch();
    const userName = useSelector(state => state.auth ? state.auth.name : null);
    const onSuccessCallBack = data => dispatch(signIn(data.token, data.name));

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
                                on_success_cb={onSuccessCallBack}
                                success_message={userName + ', you logged in successful!'}
                            />
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
};