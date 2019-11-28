import React from 'react';
import {Col, Container, Row} from "react-bootstrap";
import Card from "react-bootstrap/Card";
import * as Yup from 'yup';
import CommonForm from './../../components/common/Form';

export default () => {
    const Schema = Yup.object().shape({
        name: Yup.string()
            .min(2, 'Too Short!')
            .max(100, 'Too Long!')
            .required('Required'),
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
            name: 'name',
            type: 'text',
            label: 'Name',
        },
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
                    <h1 align={'center'}>Registration</h1>
                    <br/>
                    <Card style={{ width: '100%' }}>
                        <Card.Body>
                            <CommonForm
                                schema={Schema}
                                fields={Fields}
                                url='/auth/register'
                                submit_text='Register'
                                on_success_cb={() => {
                                    console.log('Registered')
                                }}
                                success_message={'You registered successful!'}
                            />
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
};