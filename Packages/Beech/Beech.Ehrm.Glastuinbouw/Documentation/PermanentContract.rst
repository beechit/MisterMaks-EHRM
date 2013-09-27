Permanent Contract
==================

    -
      '10004':  CAO
        required: TRUE
    -
      '10006': Sickness
        required: TRUE

                sickReportTime
    -
      '10008': general Company Rules
        required: FALSE
    -
      '10009': identification
        required: TRUE
    -
      '10010': accomodation
        required: FALSE

                accomodationRent
                unitOfTime
    -
      '10011': confidentiality agreement
        required: FALSE
    -
      '10012': work clothing
        required: FALSE
    -
      '10016': working hours
        required: TRUE

    `    hoursAWeek
        workDays
        startTime
        endTime

     '10014 Payment
             required: TRUE

        wageScaleGroup
        wageStep
        wage


      '10015 Vacation  Allowance and vacation days
            required: TRUE
-
      '10017': 'Employment, job and place of work'
        required: TRUE

            startDate
            jobTitle
    -
      '10018':  probation
        required: FALSE

            probationPeriod
            unitOTime

####### ARTICLE 18 OR 19  SHOULD BE USED NOT BOTH!!!!!!!################
    -
      '10019':
        required: TRUE
    -
      '10014':
        required: TRUE
    -
      '10035': data echange
        required: TRUE