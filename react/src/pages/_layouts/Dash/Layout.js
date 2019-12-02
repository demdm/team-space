import React from 'react';
import {Col, Container, Row} from 'react-bootstrap';
import LeftSidebar from './LeftSidebar';

export default (props) => {
    return (
        <Container>
            <br/>
            <Row>
                <Col md={3}>
                    <LeftSidebar/>
                </Col>
                <Col md={9}>
                    {props.children}
                </Col>
            </Row>
        </Container>
    );
};