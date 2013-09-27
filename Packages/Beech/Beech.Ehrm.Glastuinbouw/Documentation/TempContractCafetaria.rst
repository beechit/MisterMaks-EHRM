============================
Temp contract With Cafetaria
============================

    -
      '10000': employmentJobTitleLocation
        required: TRUE
        startDate
        endDate
        jobTitle

    -
      '10001': probation
        required: TRUE
        probationPeriod
        unitOfTime
    -
      '10002': hoursAndWorkingHours
        required: TRUE
        hoursAWeek
        workDays
        startTime
        endTime
    -
      '10004': CAO
        required: TRUE
    -
      '10005': vacationAllowance
        required: TRUE
    -
      '10006': sickness
         required: TRUE
              sickReportTime
   -
      '10007': noticePeriod
        required: TRUE
    -
      '10008': general CompanyRules
        required: FALSE
    -
      '10009': identification
        required: TRUE
    -
      '10010': accomodationRent
        required: FALSE
              accomodationRent
             UnitOfTime
    -
      '10011': confidentialityAgreement
        required: FALSE
    -
      '10012': workClothing
    -
       10014 wage
             wageScaleGroup
             WageStep
             wage

        required: TRUE

       10015 Vacation Allowance.
       required TRUE

    -
      '10020': member LTO
        required: TRUE
    -
      '10021': cafetaria
        required: TRUE
    -#### subarticles of cafetaria #####
      '10022':
        required: TRUE
    -
      '10023':
        required: TRUE
    -
      '10024':
        required: TRUE
    -
      '10025':
        required: TRUE
    -
      '10026':
        required: TRUE
    -
      '10027':
        required: TRUE
    -
      '10035': data Exchange
        required: TRUE