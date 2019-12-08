import React, {
    useEffect,
    useState,
} from 'react';
import {
    Form,
    Button,
    Alert,
    Spinner,
} from "react-bootstrap";
import { Formik } from 'formik';
import HttpClient from "../../services/HttpClient";
import {object} from "yup";
import {areObjectsEqual} from "../../helpers/object";

export default (props) => {

    // values
    const [initialValues, setInitialValues] = useState({});
    const [currentValues, setCurrentValues] = useState({});

    // message
    const [info, setInfo] = useState(null);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(null);

    // statuses
    const [isRequestHandling, setRequestHandling] = useState(false);

    // form settings
    const enableAutoFocus = props.enable_auto_focus === true;
    const enableResetFormOnSuccess = props.enable_reset_form_on_success === true;

    const schema = object().shape(props.schema);

    const hideAllMessages = (timeout = 2000) => {
        setTimeout(() => {
            setInfo(null);
            setError(null);
            setSuccess(null);
        }, timeout);
    };

    useEffect(() => {
        let fields = {};
        props.fields.forEach(field => fields[field.name] = field.value);

        setInitialValues(fields);
        setCurrentValues(fields);
    }, [
        props,
    ]);

    return (
        <Formik
            enableReinitialize
            initialValues={initialValues}
            validationSchema={schema}
            onSubmit={(values, actions) => {
                setInfo(null);
                setError(null);
                setSuccess(null);

                if (areObjectsEqual(values, currentValues)) {
                    setInfo('Nothing has been changed');
                    hideAllMessages();
                    return;
                }

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
                                props.on_success_cb(response.data, values);
                            }

                            setCurrentValues(values);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        setError('Unknown error');
                    }).finally(()  => {
                        setRequestHandling(false);
                        hideAllMessages();
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
                        info &&
                        <Alert variant='info'>
                            {info}
                        </Alert>
                    }
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

                    {props.fields.map((field, key) => (
                        <Form.Group controlId={field.name + '_' + key} key={key}>
                            {field.type !== 'hidden' &&
                                <Form.Label>{ field.label }</Form.Label>
                            }

                            {/* TEXT */}
                            {['text', 'email', 'password', 'hidden'].includes(field.type) &&
                                <Form.Control
                                    autoFocus={enableAutoFocus && key === 0}
                                    type={field.type}
                                    name={field.name}
                                    value={values[field.name] || ''}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    isInvalid={!!touched[field.name] && !!errors[field.name]}
                                    disabled={field.disabled === true}
                                />
                            }

                            {/* SELECT */}
                            {field.type === 'select' && field.options &&
                                <Form.Control
                                    as="select"
                                    name={field.name}
                                    value={values[field.name]}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    isInvalid={!!touched[field.name] && !!errors[field.name]}
                                    disabled={field.disabled === true}
                                >
                                    {
                                        Object.keys(field.options).map((value, key) => (
                                            <option
                                                key={key}
                                                value={value}
                                            >
                                                {field.options[value]}
                                            </option>
                                        ))
                                    }
                                </Form.Control>
                            }

                            <Form.Control.Feedback type="invalid">
                                {errors[field.name]}
                            </Form.Control.Feedback>
                        </Form.Group>
                    ))}

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
                            disabled={props.fields.length === props.fields.filter(field => field.disabled === true).length}
                        >
                            { props.submit_text }
                        </Button>
                    }
                </Form>
            )}
        </Formik>
    );
};