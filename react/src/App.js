import React from 'react';
import './App.css';
import "antd/dist/antd.css";
import LoginForm from './LoginForm';
import {
    PageHeader,
    Button,
    Modal,
    Row,
    Col,
    Layout,
    Menu,
} from 'antd';
import Router from "./Router";
import {Link} from "react-router-dom";

const { Header, Content, Footer } = Layout;

class App extends React.Component {
    state = { visible: false };

    showModalLoginForm = () => {
        this.setState({
            visible: true,
        });
    };

    loginFormModalOnOk = e => {
        console.log(e);
        this.setState({
            visible: false,
        });
    };

    loginFormModalOnCancel = e => {
        console.log(e);
        this.setState({
            visible: false,
        });
    };

    render() {
        return (
            <Layout>
                <PageHeader
                    ghost={false}
                    // onBack={() => window.history.back()}
                    title="Title"
                    // subTitle="This is a subtitle"
                    extra={[
                        <Button key="1">
                            <Link to="/register">Registration</Link>
                        </Button>,
                        <Button key="2" type="primary" onClick={this.showModalLoginForm}>
                            Login
                        </Button>,
                    ]}
                >
                </PageHeader>


                <Content>
                    <Router/>
                </Content>
                <Modal
                    title="Login"
                    // width="max-content"
                    footer={null}
                    visible={this.state.visible}
                    onOk={this.loginFormModalOnOk}
                    onCancel={this.loginFormModalOnCancel}
                >
                    <LoginForm/>
                </Modal>

            </Layout>
        );
    }
}

export default App;
