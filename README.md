# Laravel + Dialogflow Integration

This guide walks you through integrating **Dialogflow** with a **Laravel** application using Google Cloud APIs.

##  Prerequisites

- Laravel project setup
- Composer installed
- Google account with access to Google Cloud Console

---

## Step-by-Step Integration

### 1. Create a Project in Google Cloud Console

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click the project dropdown at the top of the page
3. Select **"New Project"**
4. Enter a name (e.g., `Chatbot-Customer-Service`)
5. Select an organization (optional)
6. Click **Create**

---

### 2. Enable Dialogflow API

1. In the Cloud Console, go to **APIs & Services** > **Library**
2. Search for **Dialogflow API**
3. Click on the result, then click **Enable**

---

### 3. Create a Service Account for Credentials

1. Navigate to **IAM & Admin** > **Service Accounts**
2. Click **+ Create Service Account**
3. Fill in:
   - **Name**: `dialogflow-service-account`
   - **Description** (optional)
4. Click **Create and Continue**
5. Assign the role:
   - **Dialogflow API Client** (or **Dialogflow API Admin** for full access)
6. Click **Continue**, then **Done**

---

### 4. Download the Credentials JSON

1. In the service account list, find your newly created account
2. Click the 3-dot menu under **Actions** > **Manage Keys**
3. Click **Add Key** > **Create new key**
4. Choose **JSON**, then click **Create**
5. Save the downloaded JSON file securely

---

### 5. Create an Agent in Dialogflow Console

1. Go to [Dialogflow Console](https://dialogflow.cloud.google.com/)
2. Select the Google Cloud project you created
3. Click **Create Agent**
4. Enter:
   - **Agent Name**: `CustomerServiceBot`
   - **Default Language**: English / Indonesian
   - **Timezone**: your region
5. Click **Create**

---

### 6. Configure Credentials in Laravel

1. Move the downloaded JSON file into your Laravel project, for example:
   ```
   storage/app/dialogflow/credentials.json
   ```
2. Add the following to your `.env` file:
   ```env
   DIALOGFLOW_PROJECT_ID=your-project-id
   DIALOGFLOW_CREDENTIALS=storage/app/dialogflow/credentials.json
   ```
   > Note: The `project_id` can be found inside the JSON credentials file.

3. In `config/services.php`, add:
   ```php
   'dialogflow' => [
       'project_id' => env('DIALOGFLOW_PROJECT_ID'),
       'credentials' => env('DIALOGFLOW_CREDENTIALS'),
   ],
   ```

---

### 7. Install Google Cloud Client Library

Run the following in your Laravel project root:

```bash
composer require google/cloud-dialogflow
```

---

##  You're All Set!

You can now interact with Dialogflow from your Laravel application using the Google Cloud Dialogflow PHP SDK.
