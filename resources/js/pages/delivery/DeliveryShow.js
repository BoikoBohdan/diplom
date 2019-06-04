import React from 'react';
import { Show, SimpleShowLayout, TextField, ImageField, required } from 'react-admin';

const DeliveryShow = (props) => (
  <Show title="Admin Show" {...props}>
    <SimpleShowLayout>
      <TextField lable="First Name" source="name" />
      <TextField lable="Last Name" source="surename" />
      <TextField lable="Email" source="email" />
      <TextField lable="Password" source="password" />
      <TextField lable="Gender" source="gender" />
      <ImageField lable="Person Picture" source="image" />
      <TextField lable="Address" source="address" />
      <TextField lable="Phone" source="phone" />
      <TextField lable="Language" source="lang" />
      <TextField lable="Currency" source="currency" />
      <TextField lable="Payment Email" source="payment_email" />
      <TextField lable="Account Holder Name" source="holder_name" />
      <TextField lable="Account Number" source="account_number" />
      <TextField lable="Name of Bank" source="bank_name" />
      <TextField lable="Bank Location" source="bank_location" />
      <TextField lable="BIC/SWIFT Code" source="code" />
    </SimpleShowLayout>
  </Show>
);

export default DeliveryShow
