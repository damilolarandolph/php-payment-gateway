import { FormControl, FormHelperText, Grid, Icon, InputLabel, OutlinedInput, TextField } from '@material-ui/core';
import React, { useEffect, useState } from 'react';
import Cards from 'react-credit-cards';
import 'react-credit-cards/es/styles-compiled.css';
import { usePaymentInputs } from 'react-payment-inputs';
import images from 'react-payment-inputs/images';




export default function CardPayment({ disablePayButton }) {
    const [formState, setFormState] = useState({
        cvc: '',
        expiry: '',
        focused: '',
        name: '',
        number: ''
    });
    const [otherErrors, setOtherErrors] = useState({
        nameError: undefined,
        pinError: undefined,
    })


    let handleCardNumberChange = (e) => {
        setFormState({ ...formState, number: e.target.value, focused: 'number' });
    }
    let handleExpiryChange = (e) => { setFormState({ ...formState, expiry: e.target.value, focused: 'expiry' }) }

    let handleCVCChange = (e) => { setFormState({ ...formState, cvc: e.target.value, focused: 'cvc' }) }

    const { meta, getCardNumberProps, getExpiryDateProps, getCVCProps, getCardImageProps } = usePaymentInputs();
    useEffect(() => {
        if (meta.error ||
            otherErrors.nameError ||
            otherErrors.pinError
        ) {
            console.log('hello');
            disablePayButton(true)
        } else {
            console.log('bye');
            disablePayButton(false)
        }
    }, [formState, disablePayButton, meta, otherErrors])
    return <Grid container spacing={3}>
        <Grid item xs={12}>
            <Cards
                cvc={formState.cvc}
                expiry={formState.expiry}
                focused={formState.focused}
                name={formState.name}
                number={formState.number} />
        </Grid>


        <Grid item xs={12}>
            <TextField helperText={otherErrors.nameError} error={otherErrors.nameError}
                onInput={(e) => {
                    let nameError = (!e.target.value || String(e.target.value).length === 0) ? 'Card holder name is required' : undefined
                    setOtherErrors({ ...otherErrors, nameError: nameError })
                    setFormState({ ...formState, name: e.target.value, focused: 'name' });
                }} variant="outlined" label="CardHolder Name" fullWidth />
        </Grid>
        <Grid item xs={12}>
            <FormControl variant="outlined" fullWidth>
                <InputLabel htmlFor="component-outlined">Card Number</InputLabel>
                <OutlinedInput startAdornment={<svg style={{ marginRight: 6 }} {...getCardImageProps({ images })} />} inputProps={{ ...getCardNumberProps({ onInput: handleCardNumberChange }) }} variant="outlined" label="Card Number" fullWidth />
                {/* <FormHelperText>{meta.erroredInputs.cardNumber}</FormHelperText> */}
            </FormControl>
        </Grid>
        <Grid item xs={12}>
            <FormControl error={otherErrors.pinError} variant="outlined" fullWidth>
                <InputLabel htmlFor="component-outlined">Card Pin</InputLabel>
                <OutlinedInput autoComplete='new-password' inputProps={{ "aria-autocomplete": 'none' }} type="password" variant="outlined" label="Card Pin" fullWidth
                    onInput={(e) => {
                        console.log(e.target.value);
                        let pinError = (!e.target.value || String(e.target.value).length === 0) ? 'Pin is required' : undefined
                        setOtherErrors({ ...otherErrors, pinError: pinError })
                        setFormState({ ...formState, pin: e.target.value });
                    }} />
                <FormHelperText>{otherErrors.pinError}</FormHelperText>
            </FormControl>
        </Grid>
        <Grid item xs={6}>
            <FormControl error={meta.erroredInputs.expiryDate} variant="outlined" fullWidth>
                <InputLabel htmlFor="component-outlined">Card Expiry</InputLabel>
                <OutlinedInput inputProps={{ ...getExpiryDateProps({ onInput: handleExpiryChange }) }} variant="outlined" label="Card Pin" fullWidth />
                <FormHelperText>{meta.erroredInputs.expiryDate}</FormHelperText>
            </FormControl>
        </Grid>
        <Grid item xs={6}>
            <FormControl error={meta.erroredInputs.cvc} variant="outlined" fullWidth>
                <InputLabel htmlFor="component-outlined">Card CVC</InputLabel>
                <OutlinedInput inputProps={{ ...getCVCProps({ onInput: handleCVCChange }) }} variant="outlined" label="Card CVC" fullWidth />
                <FormHelperText>{ }</FormHelperText>
            </FormControl>
        </Grid>
    </Grid>;
}