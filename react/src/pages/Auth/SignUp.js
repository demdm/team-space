import React from 'react';
import {Col, Container, Row} from "react-bootstrap";
import Card from "react-bootstrap/Card";
import {string} from 'yup';
import CommonForm from './../../components/common/Form';

export default () => {
    let fields = [
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

    let getParameters = {};
    window.location.href.replace(
        /[?&]+([^=&]+)=([^&]*)/gi,
        function(m, key, value) {
            getParameters[key] = value;
        }
    );

    if (getParameters['company_id']) {
        fields.push({
            name: 'company_id',
            type: 'hidden',
            value: getParameters['company_id'],
        });
    }

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
                                enable_auto_focus={true}
                                enable_reset_form_on_success={true}
                                schema={{
                                    name: string()
                                        .min(2, 'Too Short!')
                                        .max(100, 'Too Long!')
                                        .required('Required'),
                                    email: string()
                                        .email('Invalid email')
                                        .required('Required'),
                                    password: string()
                                        .min(6, 'Too Short!')
                                        .max(255, 'Too Long!')
                                        .required('Required'),
                                }}
                                fields={fields}
                                url='auth/register'
                                submit_text='Register'
                                success_message={'You registered successful!'}
                            />
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
    );
};