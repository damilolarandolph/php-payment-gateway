import React, { useContext, useState } from 'react';
import { makeStyles, ThemeProvider } from '@material-ui/styles';
import { AccountBalance, AccountBalanceWallet, Bookmark, CreditCard } from '@material-ui/icons';
import { Box, Button, CircularProgress, colors, Container, createMuiTheme, FormControl, Grid, InputLabel, OutlinedInput, Paper, Slide, Typography } from '@material-ui/core';
import { Route, Switch, useHistory } from 'react-router';
import { ProviderContext } from './Provider';
import CardPayment from './CardPayment';


const theme = createMuiTheme({
  overrides: {
    // Style sheet name ⚛️
    MuiButton: {
      // Name of the rule
      'outlined': {
        borderColor: colors.grey[400],
        fontWeight: 'bold',
        color: colors.grey[400]
      },

    },
  },
});


export const useStyles = makeStyles({
  background: {
    minWidth: "100vw",
    minHeight: "100vh",
    display: 'flex',
    padding: '50px 0',
    color: colors.lightBlue[300],
    backgroundImage: 'url(/Linth.png)',
    backgroundSize: 'cover',
    backgroundBlendMode: 'screen',
    backgroundColor: colors.lightBlue[100],
    boxSizing: 'border-box',
  },
  checkOutWidget: {
    width: 400,
    margin: "auto",
    padding: 30,
    transition: 'all .5s',
  },

  logo: {
    display: 'inline-block',
    verticalAlign: 'middle',
    fontSize: 50,
    marginRight: 10,
  },

  logoHeader: {
    verticalAlign: 'middle',
  },

  logoText: {
    fontSize: 40,
    fontWeight: 'bold',
  },

  paymentButton: {
    width: "100%",
    marginTop: 'auto',
    fontWeight: 'bold',
    backgroundColor: colors.blue[800],
    color: colors.common.white,
    paddingTop: 15,
    paddingBottom: 15,
    '&:hover': {
      transition: "all .5s",
      backgroundColor: colors.blue[900],
    }
  },
  cancelButton: {
    width: "100%",
    fontWeight: 'bold',
    marginTop: 4,
    backgroundColor: colors.amber[500],
    color: colors.common.white,
    paddingTop: 15,
    paddingBottom: 15,
    '&:hover': {
      transition: "all .5s",
      backgroundColor: colors.amber[600],
    }
  },
  selected: {

    '&.MuiButton-outlined': {
      color: colors.blue[700],
      borderColor: colors.blue[700],
      borderWidth: 2,
    }
  }



})

export default function App() {
  const classes = useStyles();
  const [route, setRoute] = useState('');
  const history = useHistory();
  const { providerState, setProviderState } = useContext(ProviderContext)
  return <ThemeProvider theme={theme}>
    {providerState.loading ? <CircularProgress /> :
      providerState.error ? providerState.error : <PaymentOptions />}
  </ThemeProvider>
};

function PaymentOptions() {
  const classes = useStyles();
  const [route, setRoute] = useState('');
  const [buttonDisabled, setButtonDisabled] = useState(true);

  const { providerState, setProviderState } = useContext(ProviderContext)
  const history = useHistory();
  return <div className={classes.background + " pattern-cross-dots-sm"}>
    <Paper elevation={4} className={classes.checkOutWidget}>
      <Container maxWidth="xl" style={{ minHeight: 700, display: 'flex', flexDirection: 'column' }}>
        <Grid container spacing={0} className={classes.logoHeader}>
          <Grid item xs={12} alignContent='center' >
            <AccountBalanceWallet className={classes.logo} />
            <Typography className={classes.logoText} style={{ display: 'inline', verticalAlign: 'middle' }}>Cov Pay</Typography>
            <Typography style={{ display: 'block', verticalAlign: 'middle' }}>{providerState.paymentDetails.consumerName}</Typography>
          </Grid>
        </Grid>
        <Grid container style={{ marginTop: 10, marginBottom: 10 }} justify="space-between" spacing={3}>
          {history.location.pathname !== '/otp' ? <>
            <Grid item>
              <Button onClick={() => { setRoute('card'); history.push('/card') }} className={route === 'card' ? classes.selected : ''} startIcon={<CreditCard />} variant="outlined">Card</Button>
            </Grid> </> : ''}
          {/* <Grid item>
            <Button onClick={() => { setRoute('bank'); history.push('/bank') }} className={route === 'bank' ? classes.selected : ''} startIcon={<AccountBalance />} variant="outlined">Bank</Button>
          </Grid> */}
        </Grid>
        <Box component="div" style={{ margin: 'auto auto' }}>
          <Switch>
            <Route exact path="/">
              <Typography>
                Please select a payment method.
                </Typography>
            </Route>
            <Route exact path="/card" >
              <CardPayment disablePayButton={setButtonDisabled} />
            </Route>
            <Route exact path="/bank" >
              <Typography>
                Please select a payment method.
                </Typography>
            </Route>
            <Route exact path="/saved" >
              <Typography>
                Please select a payment method.
                </Typography>
            </Route>
            <Route exact path="/otp">
              <OTP />
            </Route>
          </Switch>
        </Box>
      </Container>
    </Paper>
  </div>
}


function OTP() {
  const [tokenState, setTokenState] = useState("");
  let { providerState } = useContext(ProviderContext);
  const [loading, setLoading] = useState(false);

  const completeTransaction = async () => {
    setLoading(true);
    const data = {
      token: providerState.token,
      answer: tokenState
    }

    const options = {
      method: "POST",
      body: JSON.stringify(data),
      headers: {
        "Content-Type": "application/json",
      },
    }

    let res = await fetch("http://covpay.com/api/payment/finish", options);

    if (res.ok) {
      alert("Success !");
      window.close();
    } else {
      let resData = await res.json();
      console.log(resData);
      alert("Failed, " + resData['message']);
    }

    setLoading(false);

  }

  return <Grid spacing={5} container>
    <Grid item xs={12}>
      <Typography align="center">A One time password has been sent to your phone from your bank.</Typography>
    </Grid>
    <Grid item xs={12}>
      <FormControl variant="outlined" fullWidth>
        <InputLabel htmlFor="component-outlined">OTP</InputLabel>
        <OutlinedInput onInput={(e) => setTokenState(e.target.value)} required type="password" label="OTP" fullWidth />
      </FormControl>
    </Grid>
    <Grid item xs={12}>
      <Button onClick={completeTransaction} disabled={tokenState.length === 0} disableElevation fullWidth variant="contained">
        {loading ? 'Loading' : 'Verify'}
      </Button>
    </Grid>
  </Grid>
}



