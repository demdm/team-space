import React, {useEffect, useState} from 'react';
import Layout from "../_layouts/Dash/Layout";
import CommonForm from "../../components/common/Form";
import {string} from 'yup';
import {
    Col,
    Row,
    Tab,
    Nav,
} from "react-bootstrap";
import HttpClient from "../../services/HttpClient";
import UserService from "../../services/UserService";
import {useDispatch} from "react-redux";
import {changeName} from "../../actions";

export default () => {
    const dispatch = useDispatch();
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');

    const onEmailEditedSuccessCb = (response, values) => {
        dispatch(changeName(values.name));
    };

    useEffect(() => {
        HttpClient
            .preparePostRequest('auth/get-data')
            .then(response => {
                setName(response.data.name);
                setEmail(response.data.email);
            })
            .catch(error => {
                console.log(error);
            });
    },[]);

    return (
        <Layout>
            <h1>Profile</h1>

            <Tab.Container id="left-tabs-example" defaultActiveKey="name">
                <Row>
                    <Col sm={3}>
                        <Nav variant="pills" className="flex-column">
                            <Nav.Item>
                                <Nav.Link eventKey="name">Name</Nav.Link>
                            </Nav.Item>
                            <Nav.Item>
                                <Nav.Link eventKey="email">Email</Nav.Link>
                            </Nav.Item>
                            <Nav.Item>
                                <Nav.Link eventKey="password">Password</Nav.Link>
                            </Nav.Item>
                        </Nav>
                    </Col>
                    <Col sm={9}>
                        <Tab.Content>
                            <Tab.Pane eventKey="name">
                                <CommonForm
                                    schema={{
                                        name: string()
                                            .min(2, 'Too Short!')
                                            .max(255, 'Too Long!')
                                            .required('Required'),
                                    }}
                                    fields={[{
                                        name: 'name',
                                        type: 'text',
                                        label: 'Name',
                                        value: name,
                                    }]}
                                    url='auth/edit-name'
                                    submit_text='Save'
                                    success_message='Name updated!'
                                    on_success_cb={onEmailEditedSuccessCb}
                                />
                            </Tab.Pane>
                            <Tab.Pane eventKey="email">
                                <CommonForm
                                    schema={{
                                        email: string()
                                            .email('Invalid email')
                                            .required('Required'),
                                    }}
                                    fields={[{
                                        name: 'email',
                                        type: 'email',
                                        label: 'Email',
                                        value: email
                                    }]}
                                    url='auth/edit-email'
                                    submit_text='Save'
                                    success_message='Email updated!'
                                />
                            </Tab.Pane>
                            <Tab.Pane eventKey="password">
                                <CommonForm
                                    schema={{
                                        password: string()
                                            .min(6, 'Too Short!')
                                            .max(255, 'Too Long!')
                                            .required('Required'),
                                    }}
                                    fields={[{
                                        name: 'password',
                                        type: 'password',
                                        label: 'New password',
                                    }]}
                                    url='auth/edit-password'
                                    submit_text='Save'
                                    success_message='Password updated!'
                                />
                            </Tab.Pane>
                        </Tab.Content>
                    </Col>
                </Row>
            </Tab.Container>
        </Layout>
    );
}