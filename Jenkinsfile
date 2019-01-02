#!groovy

import groovy.json.JsonSlurper
import net.sf.json.JSONArray
import net.sf.json.JSONObject

properties([buildDiscarder(logRotator(artifactDaysToKeepStr: '', artifactNumToKeepStr: '', daysToKeepStr: '1', numToKeepStr: '4')), disableConcurrentBuilds()])

def notifySlack(color, message) {
    withCredentials([string(credentialsId: 'SLACK_NOTIFICATION_KEY', variable: 'SLACK_NOTIFICATION_KEY')]) {
        JSONArray attachments = new JSONArray()
        JSONObject attachment = new JSONObject()
        JSONArray fields = new JSONArray()
        JSONObject buildField = new JSONObject()
        JSONObject nodeField = new JSONObject()

        Date currentDate  = new Date()

        attachment.put('footer', env.JENKINS_URL)
        attachment.put('title', URLDecoder.decode(currentBuild.fullProjectName))
        attachment.put('title_link', env.BUILD_URL)
        attachment.put('text', message)
        attachment.put('ts', currentDate.getTime() / 1000)
        attachment.put('color', color)

        buildField.put('title', 'Job')
        buildField.put('value', env.BUILD_DISPLAY_NAME)
        buildField.put('short', true)
        fields.add(buildField)

        nodeField.put('title', 'Node')
        nodeField.put('value', env.NODE_NAME)
        nodeField.put('short', true)
        fields.add(nodeField)

        attachment.put('fields', fields)

        attachments.add(attachment)

        slackSend (
            channel: '#tcp_build',
            baseUrl: 'https://iamproperty.slack.com/services/hooks/jenkins-ci/',
            token: SLACK_NOTIFICATION_KEY,
            attachments: attachments.toString()
        )
    }
}

def notifyPositive(message) {
    notifySlack('#A9CA44', message)
}

def notifyInfo(message) {
    notifySlack('#2196f3', message)
}

def notifyWarning(message) {
    notifySlack('#E3B33C', message)
}

def notifyDanger(message) {
    notifySlack('#DE7163', message)
}

def cleanup() {
    try {
        sh 'docker-compose -f "scripts/docker-compose-jenkins.yml" down'
    } catch (exc) {}
    try {
        sh "docker network rm `docker network ls -q --filter type=custom`"
    } catch (exc) {}
    cleanWs()

    if (currentBuild.result == null || currentBuild.result == 'SUCCESS') {
        notifyPositive (':sunny: Build Complete and Passing!')
    }
    else if (currentBuild.result == 'UNSTABLE') {
        notifyWarning (':sun_behind_cloud: Build Complete but Unstable!')
    }
    else {
        notifyDanger (':rain_cloud: Build Failed!')
    }
}
/*
def dockerBuild(name, tag, file) {
    return docker.build(
        "${name}:${tag}",
        "-f ${file} --build-arg AWS_CREDENTIALS=\${AWS_CREDENTIALS} --no-cache ."
    )
}

def dockerPush(name, tag, registry) {
    sh "docker tag ${name}:${tag} ${registry}/${name}:${tag}"
    sh "docker push ${registry}/${name}:${tag}"
}
*/

def dockerPull(registry, name, tag) {
    sh "docker pull ${registry}/${name}:${tag}"
}

def isDeployable() {
    return env.BRANCH_NAME == "Development"
}

def isPublishable() {
    //return isDeployable() || env.BRANCH_NAME == "master"
    return false
}

def buildContainer() {
    sh 'cd scripts && docker-compose -f "docker-compose-jenkins.yml" up -d && cd ../'
}

def awsRegistry = "135587954537.dkr.ecr.eu-west-1.amazonaws.com"
def awsRegion = "eu-west-1"
def dockerImage = "isg-tcp-laravel"
def dockerTag = "latest"
def repository = "tcp-laravel"
def branchName
def dockerDist
def gitCommit

pipeline {
  agent any
  stages {

    stage("Start") {
      steps {
       sh "chmod 744 ./scripts/*.sh"
       notifyInfo (':hourglass_flowing_sand: Build Started!')
      }
    }

    // Checkout from Bitbucket
    stage("Checkout") {
      failFast true
      parallel {
        stage("Code") {
          steps {
            checkout scm
            script {
              branchName = env.BRANCH_NAME
              gitCommit = sh(returnStdout: true, script: 'git rev-parse HEAD').trim()
            }
          }
        }
        stage("Container") {
          steps {
            withCredentials([
              string(credentialsId: "AWS_ACCESS_KEY_ID", variable: "AWS_ACCESS_KEY_ID"),
              string(credentialsId: "AWS_SECRET_ACCESS_KEY", variable: "AWS_SECRET_ACCESS_KEY"),
            ]) {
              sh "aws ecr get-login --no-include-email --region ${awsRegion} > dockerLogin.sh"
              sh "sh ./dockerLogin.sh"
              sh "rm ./dockerLogin.sh"
              dockerPull(awsRegistry, dockerImage, dockerTag)
            }
          }
        }
      }
    }
    stage("Boot") {
      steps {
        // This block sometimes fails with weird apt issues, but a retry seems to sort it.
        script {
          try {
            // Boot container and configure
            retry(3) {
              buildContainer()
            }
          }
          catch(exc) {
            // tear down current container
            sh 'docker-compose -f "scripts/docker-compose-jenkins.yml" down'
            throw exc
          }
        }
      }
    }
    stage("Dependencies") {
      failFast true
      parallel {
        stage("Composer") {
          steps {
            sh 'docker-compose -f "scripts/docker-compose-jenkins.yml" exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site && composer install --no-progress --no-interaction --optimize-autoloader --no-ansi"'
          }
        }
        stage("Node / NPM") {
          steps {
            sh 'docker-compose -f "scripts/docker-compose-jenkins.yml" exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site && npm install"'
          }
        }
      }
    }
    // Build the environment by adding the current application to the docker container
    stage("Build") {
      failFast true
      stages {
        stage("Webpack") {
          steps {
            sh 'docker-compose -f "scripts/docker-compose-jenkins.yml" exec -T webserver /bin/bash -c "cd /data/srv/nginx/laravel.tcp-ltd.co.uk/site && npm run development-ci"'
          }
        }
        stage("Application") {
          steps {
            // Fix permissions
            sh 'chmod -R 777 ./storage/'
            sh "chmod 744 ./scripts/*.sh"
            // First time setup
            sh './scripts/firstrun-ci.sh'
            // TODO: Seeding can be done in parallel? maybe
          }
        }
      }
    }

    // Run Tests
    stage("Test") {
      parallel {
        stage("PHPLint") {
          steps {
            sh './scripts/test-ci.sh lint'
          }
        }
        stage("PHPUnit") {
          steps {
            sh './scripts/test-ci.sh phpunit'
          }
        }
        stage("PHP Code Sniff") {
          steps {
            sh './scripts/test-ci.sh phpcs'
          }
        }
        stage("PHP Mess Detection") {
          steps {
            sh './scripts/test-ci.sh phpmd'
          }
        }
        stage("ESLint") {
          steps {
            sh './scripts/test-ci.sh eslint'
          }
        }
        stage("Mocha") {
          steps {
            sh './scripts/test-ci.sh mocha'
          }
        }
      }
    }
  }
  post {
    always {
      // Cleanup empty phpmd.xml so test isn't marked as failed
      sh './scripts/clean-empty-reports.sh'

      // Export test output
      junit 'report/*.xml'

      // PHPUnit Clover Coverage Report
      step([
        $class: 'CloverPublisher',
        cloverReportDir: 'report/coverage',
        cloverReportFileName: 'coverage.xml',
        healthyTarget: [methodCoverage: 25, conditionalCoverage: 80, statementCoverage: 25],
        unhealthyTarget: [methodCoverage: 10, conditionalCoverage: 50, statementCoverage: 10],
        failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]
      ])

      // Create artifact of test screenshots
      archiveArtifacts artifacts: 'report/images/**/*.png', allowEmptyArchive: true, fingerprint: true
    }
    cleanup {
      cleanup();
      echo 'Finished post'
    }
  }
}
