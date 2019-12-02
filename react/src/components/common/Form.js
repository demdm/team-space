import React, {useState} from 'react';
import {
    Form,
    Button,
    Alert,
    Spinner,
} from "react-bootstrap";
import { Formik } from 'formik';
import HttpClient from "../../services/HttpClient";
import {object} from "yup";

export default (props) => {
    let fields = {};
    props.fields.forEach(field => fields[field.name] = field.value || '');

    const [initialValues] = useState(fields);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);
    const [isRequestHandling, setRequestHandling] = useState(false);
    const enableAutoFocus = props.enable_auto_focus === true;
    const enableResetFormOnSuccess = props.enable_reset_form_on_success === true;
    const schema = object().shape(props.schema);

    return (
        <Formik
            initialValues={initialValues}
            validationSchema={schema}
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
                            if (enableResetFormOnSuccess) {
                                actions.resetForm();
                            }

                            if (typeof props.on_success_cb === 'function') {
                                props.on_success_cb(response.data.data);
                            }
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

                    {props.fields.map((field, key) => {
                        return (
                            <Form.Group controlId={field.name + '_' + key} key={key}>
                                <Form.Label>{ field.label }</Form.Label>
                                <Form.Control
                                    autoFocus={ enableAutoFocus && key === 0 }
                                    type={ field.type }
                                    name={ field.name }
                                    value={ values[field.name] || field.value || '' } // костляка, исправить!!!
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