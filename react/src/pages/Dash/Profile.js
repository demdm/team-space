import React, {useEffect, useState} from 'react';
import Layout from "../_layouts/Dash/Layout";
import CommonForm from "../../components/common/Form";
import {string} from 'yup';
import {
    Col,
    Row,
    Tab,
    Nav, Alert, ListGroup,
} from "react-bootstrap";
import HttpClient from "../../services/HttpClient";
import {useDispatch, useSelector} from "react-redux";
import {changeName, setCompanyId} from "../../actions";
import UserService from "../../services/UserService";

export default () => {
    const dispatch = useDispatch();
    const userId = UserService.id;
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [company, setCompany] = useState(null);
    const companyId = useSelector(state => state.company.id);
    const [companyCreator, setCompanyCreator] = useState(null);
    const [companyOwner, setCompanyOwner] = useState(null);
    const [position, setPosition] = useState('');
    const [role, setRole] = useState('');
    const [roleOptions, setRoleOptions] = useState({});

    const onNameEditedSuccessCb = (response, values) => {
        dispatch(changeName(values.name));
    };

    const onCompanyEditedSuccessCb = (response, values) => {
        dispatch(setCompanyId(values.company_id));
    };

    useEffect(() => {
        HttpClient
            .preparePostRequest('user/profile/get-data')
            .then(response => {
                setName(response.data.name);
                setEmail(response.data.email);
                setPosition(response.data.position);
                setRole(response.data.role);
                setRoleOptions(response.data.roles);
                setCompany(response.data.company);
                setCompanyCreator(response.data.companyCreator);
                setCompanyOwner(response.data.companyOwner);

                if (response.data.company) {
                    dispatch(setCompanyId(response.data.company.id));
                }
            })
            .catch(error => {
                console.error(error);
            });
    },[dispatch]);

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
                            <Nav.Item>
                                <Nav.Link eventKey="company">Company</Nav.Link>
                            </Nav.Item>
                            <Nav.Item>
                                <Nav.Link eventKey="position">Position</Nav.Link>
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
                                    url='user/profile/edit-name'
                                    submit_text='Save'
                                    success_message='Name updated!'
                                    on_success_cb={onNameEditedSuccessCb}
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
                                    url='user/profile/edit-email'
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
                                    url='user/profile/edit-password'
                                    submit_text='Save'
                                    success_message='Password updated!'
                                />
                            </Tab.Pane>
                            <Tab.Pane eventKey="company">
                                <Alert variant="info">
                                    <Alert.Heading>Company management</Alert.Heading>
                                    <p>
                                        The first one who created the company becomes the owner. <br/>
                                        The owner can give the own right to another user.
                                    </p>
                                    <ListGroup>
                                        {companyCreator && companyCreator.name &&
                                            <ListGroup.Item>Creator - {companyCreator.name}</ListGroup.Item>
                                        }
                                        {companyOwner && companyOwner.name &&
                                            <ListGroup.Item>Owner - {companyOwner.name}</ListGroup.Item>
                                        }
                                        {company &&
                                            <>
                                                <ListGroup.Item>Created - {company.created_at}</ListGroup.Item>
                                                <ListGroup.Item>Updated - {company.updated_at}</ListGroup.Item>
                                            </>
                                        }
                                    </ListGroup>
                                </Alert>
                                <CommonForm
                                    schema={{
                                        company_name: string()
                                            .min(2, 'Too Short!')
                                            .max(255, 'Too Long!')
                                            .required('Required'),
                                    }}
                                    fields={[
                                        {
                                            name: 'company_name',
                                            type: 'text',
                                            label: 'Company name',
                                            value: (company && company.name) || '',
                                            disabled: companyOwner && userId !== companyOwner.id,
                                        },
                                    ]}
                                    init_values={{
                                        company_id: companyId,
                                    }}
                                    url='user/profile/edit-company'
                                    submit_text={company ? 'Update' : 'Create' }
                                    success_message='Company saved!'
                                    on_success_cb={onCompanyEditedSuccessCb}
                                />
                            </Tab.Pane>
                            <Tab.Pane eventKey="position">
                                <CommonForm
                                    schema={{
                                        role: string()
                                            .oneOf(Object.keys(roleOptions))
                                            .required(),
                                        position: string()
                                            .min(0, 'Too Short!')
                                            .max(255, 'Too Long!'),
                                    }}
                                    fields={[
                                        {
                                            name: 'role',
                                            type: 'select',
                                            options: roleOptions,
                                            label: 'Position name',
                                            value: role,
                                        },
                                        {
                                            name: 'position',
                                            type: 'text',
                                            label: 'Position description',
                                            value: position,
                                        },
                                    ]}
                                    url='user/profile/edit-position'
                                    submit_text='Save'
                                    success_message='Position data updated!'
                                />
                            </Tab.Pane>
                        </Tab.Content>
                    </Col>
                </Row>
            </Tab.Container>
        </Layout>
    );
}