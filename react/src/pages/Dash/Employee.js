import React, {useEffect, useState} from 'react';
import Layout from "../_layouts/Dash/Layout";
import {Container, Row, Col, ListGroup, Alert} from "react-bootstrap";
import HttpClient from "../../services/HttpClient";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCheck, faTimes } from '@fortawesome/free-solid-svg-icons'
import CommonForm from "../../components/common/Form";
import {string} from "yup";

export default () => {
    const [perPage, setPerPage] = useState(null);
    const [page, setPage] = useState(null);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [role, setRole] = useState('');
    const [presenceStatus, setPresenceStatus] = useState('');
    const [workType, setWorkType] = useState('');
    const [sortField, setSortField] = useState('');

    const [totalPages, setTotalPages] = useState(0);
    const [list, setList] = useState([]);
    const [listCount, setListCount] = useState(0);
    const [roles, setRoles] = useState({});
    const [presenceStatuses, setPresenceStatuses] = useState({});
    const [workTypes, setWorkTypes] = useState({});
    const [sortFields, setSortFields] = useState({});

    const activeIcon = <FontAwesomeIcon icon={faCheck} />;
    const notActiveIcon = <FontAwesomeIcon icon={faTimes} />;

    let onEmployeeFilterSuccessCb = (response, values) => {};

    useEffect(() => {
        HttpClient
            .preparePostRequest('user/employee', {
                per_page: perPage,
                page,
                name,
                email,
                role,
                presence_status: presenceStatus,
                work_type: workType,
                sort_field: sortField,
            })
            .then(response => {
                setPerPage(response.data.data.per_page);
                setPage(response.data.data.page);
                setName(response.data.data.name);
                setEmail(response.data.data.email);
                setRole(response.data.data.role);
                setPresenceStatus(response.data.data.presence_status);
                setWorkType(response.data.data.work_type);
                setSortField(response.data.data.sort_field);

                setList(response.data.list);
                setTotalPages(response.data.total_pages);
                setListCount(response.data.list_count);
                setRoles(response.data.roles);
                setPresenceStatuses(response.data.presence_statuses);
                setWorkTypes(response.data.work_types);
                setSortFields(response.data.sort_fields);
            })
            .catch(error => console.error(error));
    }, []);

    return (
        <Layout>
            <h1>Employee</h1>
            <Container>
                <Row>
                    <Col sm={3}>
                        <CommonForm
                            schema={{
                                name: string()
                                    .min(2, 'Too Short!')
                                    .max(255, 'Too Long!'),
                            }}
                            fields={[
                                {
                                    name: 'page',
                                    type: 'number',
                                    placeholder: 'Page',
                                    min: 1,
                                    value: page,
                                    hint: page + ' page from ' + totalPages,
                                },
                                {
                                    name: 'perPage',
                                    type: 'number',
                                    min: 1,
                                    max: 50,
                                    placeholder: 'Per page',
                                    value: perPage,
                                    hint: (perPage > listCount ? listCount : perPage) + ' items from ' + listCount,
                                },
                                {
                                    name: 'name',
                                    type: 'text',
                                    placeholder: 'Name',
                                    value: name,
                                },
                                {
                                    name: 'email',
                                    type: 'text',
                                    placeholder: 'Email',
                                    value: email,
                                },
                                {
                                    name: 'role',
                                    type: 'select',
                                    options: { ...{'': '- Position -'}, ...roles },
                                    value: role,
                                },
                                {
                                    name: 'presence_status',
                                    type: 'select',
                                    options: { ...{'': '- Presence -'}, ...presenceStatuses },
                                    value: presenceStatus,
                                },
                                {
                                    name: 'work_type',
                                    type: 'select',
                                    options: { ...{'': '- Work -'}, ...workTypes },
                                    value: workType,
                                },
                                {
                                    name: 'sort_field',
                                    type: 'select',
                                    options: { ...{'': '- Sort -'}, ...sortFields },
                                    value: sortField
                                },
                            ]}
                            url='user/employee'
                            submit_text='Filter'
                            on_success_cb={onEmployeeFilterSuccessCb}
                        />
                    </Col>
                    <Col sm={9}>
                        { list.length > 0
                            ? list.map((employee, key) => {
                                return (
                                    <div key={key}>
                                        <ListGroup>
                                            <ListGroup.Item>{employee.name}</ListGroup.Item>
                                            <ListGroup.Item>{employee.email}</ListGroup.Item>
                                        </ListGroup>
                                        <br/>
                                    </div>
                                )
                            })
                            : (
                                <Alert variant='info'>
                                    List is empty
                                </Alert>
                            )
                        }
                    </Col>
                </Row>
            </Container>
        </Layout>
    );
};