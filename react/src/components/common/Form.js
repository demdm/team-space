import React, {useState} from 'react';
import { Form } from "react-bootstrap";
import Button from "react-bootstrap/Button";
import { Formik } from 'formik';
import Alert from "react-bootstrap/Alert";
import Spinner from "react-bootstrap/Spinner";
import HttpClient from "../../services/HttpClient";

let CommonForm = (props) => {
    let fields = {};
    props.fields.forEach(field => fields[field.name] = '');

    const [initialValues] = useState(fields);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);
    const [isRequestHandling, setRequestHandling] = useState(false);

    return (
        <Formik
            initialValues={initialValues}
            validationSchema={props.schema}
            onSubmit={(values, actions) => {
                setRequestHandling(true);

                HttpClient
                    .preparePostRequest(props.url, values)
                    .then(response => {
                        setSuccess(response.data.success);
                        setError(null);

                        // failed
                        if (!response.data.success) {
                            if (response.data.validation_errors) {
                                actions.setErrors(response.data.validation_errors);
                            }

                            if (response.data.error) {
                                setError(response.data.error);
                            }
                        } else {
                        // success
                            actions.resetForm();

                            props.on_success_cb(response.data.data);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        setError('Unknown error');
                    }).finally(()  => {
                        setRequestHandling(false);
                    });
            }}
        >
            {({
                  handleSubmit,
                  handleChange,
                  handleBlur,
                  values,
                  touched,
                  isValid,
                  errors,
            }) => (
                <Form noValidate onSubmit={handleSubmit}>
                    {
                        error &&
                        <Alert variant='danger'>
                            {error}
                        </Alert>
                    }
                    {
                        success && props.success_message &&
                        <Alert variant='success'>
                            {props.success_message}
                        </Alert>
                    }

                    {props.fields.map((field, i) => {
                        return (
                            <Form.Group controlId={field.name + '_' + i} key={i}>
                                <Form.Label>{ field.label }</Form.Label>
                                <Form.Control
                                    autoFocus={ i === 0 }
                                    type={ field.type }
                                    name={ field.name }
                                    value={ values[field.name] }
                                    onChange={ handleChange }
                                    onBlur={ handleBlur }
                                    isInvalid={ !!touched[field.name] && !!errors[field.name] }
                                />
                                <Form.Control.Feedback type="invalid">
                                    {errors[field.name]}
                                </Form.Control.Feedback>
                            </Form.Group>
                        )
                    })}

                    { isRequestHandling
                        ?
                        <Button variant="primary" disabled>
                            <Spinner
                                as="span"
                                animation="grow"
                                size="sm"
                                role="status"
                                aria-hidden="true"
                                variant="light"
                            />
                            Loading...
                        </Button>
                        :
                        <Button
                            variant="primary"
                            type="submit"
                        >
                            { props.submit_text }
                        </Button>
                    }
                </Form>
            )}
        </Formik>
    );
};

export default CommonForm;
