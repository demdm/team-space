import React from 'react';
import './App.css';
import "antd/dist/antd.css";
import LoginForm from './LoginForm';
import {
    PageHeader,
    Button,
    Modal,
} from 'antd';

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
            <div className="App">
                <div>
                    <PageHeader
                        ghost={false}
                        onBack={() => window.history.back()}
                        title="Title"
                        subTitle="This is a subtitle"
                        extra={[
                            <Button key="1" type="primary" onClick={this.showModalLoginForm}>Open Modal</Button>,
                        ]}
                    >
                    </PageHeader>
                </div>

                <Modal
                    title="Login"
                    width="max-content"
                    footer={null}
                    visible={this.state.visible}
                    onOk={this.loginFormModalOnOk}
                    onCancel={this.loginFormModalOnCancel}
                >
                    <LoginForm/>
                </Modal>
            </div>
        );
    }
}

export default App;
